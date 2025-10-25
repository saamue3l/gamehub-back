<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Forum;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use App\Services\SuccessService;
use Carbon\Carbon;
use Http\Discovery\Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;


class ForumController extends Controller
{


    protected SuccessService $successService;

    /**
     * @param SuccessService $successService
     */
    public function __construct(SuccessService $successService)
    {
        $this->successService = $successService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllForums(Request $request): \Illuminate\Http\JsonResponse
    {
        $forums = Cache::remember('forums', 60, function () {
            return Forum::all();
        });
        return response()->json($forums)->header('Cache-Control', 'public, max-age=30')->setEtag(sizeof($forums));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createForum(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => "Vous n'êtes pas autorisé à créer un forum"], 403);
        }

        $request->validate([
            'name' => 'required|string',
        ]);

        $name = $request->input('name');
        $forum = new Forum();
        $forum->name = $name;
        $forum->save();

        Cache::forget('forums');

        return response()->json(['message' => 'Nouveau forum créé', 'forumId' => $forum->id]);
    }

    /**
     * @param Request $request
     * @param int $forumId
     * @return \Illuminate\Http\JsonResponse
     */
    public function editForum(Request $request, int $forumId): \Illuminate\Http\JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => "Vous n'êtes pas autorisé à éditer un forum"], 403);
        }

        $forum = Forum::find($forumId);
        if ($forum === null) {
            return response()->json(['message' => "Le forum n'a pas été trouvé"], 404);
        }

        $request->validate([
            'name' => 'string'
        ]);

        $name = $request->input('name');
        $forum->name = $name;
        $forum->save();

        Cache::forget('forums');

        return response()->json();
    }

    /**
     * @param Request $request
     * @param int $forumId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeForum(Request $request, int $forumId): \Illuminate\Http\JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => "Vous n'êtes pas autorisé à supprimer un forum"], 403);
        }

        $forum = Forum::find($forumId);
        if ($forum === null) {
            return response()->json(['message' => "Le forum n'a pas été trouvé"], 404);
        }

        $forum->delete();
        Cache::forget('forums');
        return response()->json();
    }

    /**
     * @param int $forumId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getForum(int $forumId): \Illuminate\Http\JsonResponse
    {
        $forum = Forum::where('id', $forumId)
            ->with([
                'topics' => function ($query) {
                                if (!request()->user()->isAdmin()) {
                        $query->where('visible', true);
                    }
                },
                'topics.posts' => function ($query) {
                                if (!request()->user()->isAdmin()) {
                        $query->where('visible', true);
                    }
                },
                'topics.creator'
            ])
            ->first();

        // 404 if the forum is not found
        if ($forum === null) {
            return response()->json(['message' => "Le forum n'a pas été trouvé. Il a peut être été supprimé"], 404);
        }

        if (!request()->user()->isAdmin()) {
            $forum->topics->each(function ($topic) {
                $topic->makeHidden(['visible']);
                $topic->posts->each(function ($post) {
                  $post->makeHidden(['visible']);
                });
            });
        }

        $forum->topics->each(function ($topic) {
            if ($topic->creator && $topic->creator->picture) {
                if (!str_starts_with($topic->creator->picture, 'storage/') && !str_starts_with($topic->creator->picture, '/storage/')) {
                    $topic->creator->picture = url('storage/' . $topic->creator->picture);
                }
            }
        });

        return response()->json($forum);
    }

    /**
     * @param int $topicId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTopic(int $topicId): \Illuminate\Http\JsonResponse
    {
        $topic = Topic::where('id', $topicId)
            ->with([
                'posts' => function ($query) {
                    if (!request()->user()->isAdmin()) {
                        $query->where('visible', true);
                    }
                },
                'posts.user',
                'posts.reactions.user', // Load reaction users
                'posts.reactions.reactiontype:id,emoji' // Load only emoji from reactiontype
            ])
            ->first();

        $topic->posts->each(function ($post) {
                $post->setRelation('reactions', $post->getGroupedReactions());

                if (!request()->user()->isAdmin()) {
                $post->makeHidden(['visible']);
            }
        });

        $topic->posts->each(function ($post) {
            if ($post->user && $post->user->picture) {
                if (!str_starts_with($post->user->picture, 'storage/') && !str_starts_with($post->user->picture, '/storage/')) {
                    $post->user->picture = url('storage/' . $post->user->picture);
                }
            }
        });

        return response()->json($topic);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTopic(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'forumId' => 'required|integer',
            'title' => 'required|string',
            'content' => 'required|string'
        ]);


        $forumId = $request->input('forumId');
        $title = $request->input('title');
        $content = $request->input('content');

        $user = $request->user();
        $forum = Forum::find($forumId);

        if ($forum === null) {
            return response()->json(['message' => "Le forum n'a pas été trouvé"], 404);
        }

        $topic = new Topic();
        $topic->title = $title;
        $topic->forumId = $forumId;
        $topic->creator()->associate($user);
        $topic->save();

        $post = new Post();
        $post->content = $content;
        $post->topicId = $topic->id;
        $post->userId = $user->id;
        $post->save();

        $result = $this->successService->handleAction($user, 'CREATE_TOPIC');

        return response()->json([
            'message' => 'Nouveau sujet créé',
            'topicId' => $topic->id,
            'xpGained' => $result['xpGained'],
            'newSuccess' => $result['newSuccess']]);
    }

    /**
     * @param Request $request
     * @param int $topicId
     * @return \Illuminate\Http\JsonResponse
     */
    public function editTopic(Request $request, int $topicId) {
        $topic = Topic::find($topicId);
        if ($topic === null) {
            return response()->json(['message' => "Le sujet de discussion n'a pas été trouvé"], 404);
        }

        if ($request->user()->id !== $topic->creatorId && !$request->user()->isAdmin()) {
            return response()->json(['message' => "Vous n'êtes pas autorisé à modifier ce sujet"], 403);
        }

        $request->validate([
            'title' => 'string'
        ]);

        $title = $request->input('title');
        $topic->title = $title;
        $topic->save();
        return response()->json();
    }

    /**
     * @param Request $request
     * @param int $topicId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeTopic(Request $request, int $topicId): \Illuminate\Http\JsonResponse
    {
        $topic = Topic::find($topicId);
        if ($topic === null) {
            return response()->json(['message' => "Le sujet de discussion n'a pas été trouvé"], 404);
        }

        if ($request->user()->id !== $topic->creatorId && !$request->user()->isAdmin()) {
            return response()->json(['message' => "Vous n'êtes pas autorisé à supprimer ce sujet"], 403);
        }

        $topic->delete();
        return response()->json();
    }

    /**
     * @param Request $request
     * @param int $topicId
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPost(Request $request, int $topicId): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $topic = Topic::find($topicId);

        if ($topic === null) {
            return response()->json(['message' => "Le sujet de discussion n'a pas été trouvé"], 404);
        }

        $content = $request->input('content');

        $user = $request->user();

        $post = new Post();
        $post->content = $content;
        $post->topicId = $topic->id;
        $post->userId = $user->id;
        $post->save();

        $result = $this->successService->handleAction($user, 'POST_MESSAGE');

        return response()->json([
            'message' => 'Post ajouté',
            'postId' => $post->id,
            'xpGained' => $result['xpGained'],
            'newSuccess' => $result['newSuccess']]);
    }

    /**
     * @param Request $request
     * @param int $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function editPost(Request $request, int $postId): \Illuminate\Http\JsonResponse
    {
        $post = Post::find($postId);
        if ($post === null) {
            return response()->json(['message' => "Le message n'a pas été trouvé"], 404);
        }

        if ($request->user()->id !== $post->userId && !$request->user()->isAdmin()) {
            return response()->json(['message' => "Vous n'êtes pas autorisé à modifier ce message"], 403);
        }

        $request->validate([
            'content' => 'string|required|min:1'
        ]);

        $content = $request->input('content');
        $post->content = $content;
        $post->save();
        return response()->json();
    }

    /**
     * @param Request $request
     * @param int $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removePost(Request $request, int $postId): \Illuminate\Http\JsonResponse
    {
        $post = Post::find($postId);
        if ($post === null) {
            return response()->json(['message' => "Le message n'a pas été trouvé"], 404);
        }

        if ($request->user()->id !== $post->userId && !$request->user()->isAdmin()) {
            return response()->json(['message' => "Vous n'êtes pas autorisé à supprimer ce message"], 403);
        }

        $post->delete();
        return response()->json();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchInForums(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'search' => 'required|string'
        ]);

        $search = $request->input('search');

        $topics = Topic::search($search)->query(fn (Builder $query) => $query->with('creator'))->get();
        $topics->each(function ($topic) {
            $topic->forumId = $topic->forum->id;
        });
        $posts = Post::search($search)->query(fn (Builder $query) => $query->with('topic', 'topic.creator', 'user'))->get();

        return response()->json(['topics' => $topics, 'posts' => $posts]);
    }
}
