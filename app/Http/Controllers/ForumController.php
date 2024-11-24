<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Forum;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Carbon\Carbon;
use Http\Discovery\Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;

class ForumController extends Controller
{
    /**
     * @param Request $request Empty
     * @return \Illuminate\Http\JsonResponse The list of all of the forums
     */
    public function getAllForums(Request $request): \Illuminate\Http\JsonResponse
    {
        // Put in cache
        $forums = Cache::remember('forums', 60, function () {
            return Forum::all();
        });
        // Return the forums and say to the client to cache the response
        return response()->json($forums)->header('Cache-Control', 'public, max-age=30')->setEtag(sizeof($forums));
    }

    public function createForum(Request $request): \Illuminate\Http\JsonResponse
    {
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

    public function editForum(Request $request, int $forumId): \Illuminate\Http\JsonResponse
    {
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

    public function removeForum(Request $request, int $forumId): \Illuminate\Http\JsonResponse
    {
        $forum = Forum::find($forumId);
        if ($forum === null) {
            return response()->json(['message' => "Le forum n'a pas été trouvé"], 404);
        }

        $forum->delete();
        Cache::forget('forums');
        return response()->json();
    }

    public function getForum(int $forumId): \Illuminate\Http\JsonResponse
    {
        // Fetch the forum with topics, filtering visible topics for non-admins
        $forum = Forum::where('id', $forumId)
            ->with([
                'topics' => function ($query) {
                    // Only the "visible" == true to non admin users
                    if (!request()->user()->isAdmin()) {
                        $query->where('visible', true);
                    }
                },
                'topics.posts' => function ($query) {
                    // Only the "visible" == true to non admin users
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

        // Remove the "visible" column for non-admin users
        if (!request()->user()->isAdmin()) {
            $forum->topics->each(function ($topic) {
                $topic->makeHidden(['visible']);
                $topic->posts->each(function ($post) {
                  $post->makeHidden(['visible']);
                });
            });
        }

        return response()->json($forum);
    }

    public function getTopic(int $topicId): \Illuminate\Http\JsonResponse
    {
        // Fetch the topic with posts and related data in one query
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

        // Process each post to replace reactions with grouped structure from model method
        $topic->posts->each(function ($post) {
            // Use the model function to get grouped reactions
            $post->setRelation('reactions', $post->getGroupedReactions());

            // Hide the "visible" field for non-admin users
            if (!request()->user()->isAdmin()) {
                $post->makeHidden(['visible']);
            }
        });

        return response()->json($topic);
    }

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

        // Create the topic
        $topic = new Topic();
        $topic->title = $title;
        $topic->forumId = $forumId;
        $topic->creator()->associate($user);
        $topic->save();

        // Create first post
        $post = new Post();
        $post->content = $content;
        $post->topicId = $topic->id;
        $post->userId = $user->id;
        $post->save();

        return response()->json(['message' => 'Nouveau sujet créé', 'topicId' => $topic->id]);
    }

    public function editTopic(Request $request, int $topicId) {
        $topic = Topic::find($topicId);
        if ($topic === null) {
            return response()->json(['message' => "Le sujet de discussion n'a pas été trouvé"], 404);
        }

        // If user is not the creator of the topic and not an admin, return 403
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

    public function removeTopic(Request $request, int $topicId): \Illuminate\Http\JsonResponse
    {
        $topic = Topic::find($topicId);
        if ($topic === null) {
            return response()->json(['message' => "Le sujet de discussion n'a pas été trouvé"], 404);
        }

        // If user is not the creator of the topic and not an admin, return 403
        if ($request->user()->id !== $topic->creatorId && !$request->user()->isAdmin()) {
            return response()->json(['message' => "Vous n'êtes pas autorisé à supprimer ce sujet"], 403);
        }

        $topic->delete();
        return response()->json();
    }

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

        return response()->json(['message' => 'Post ajouté', 'postId' => $post->id]);
    }

    public function editPost(Request $request, int $postId): \Illuminate\Http\JsonResponse
    {
        $post = Post::find($postId);
        if ($post === null) {
            return response()->json(['message' => "Le message n'a pas été trouvé"], 404);
        }

        // If user is not the creator of the post and not an admin, return 403
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

    public function removePost(Request $request, int $postId): \Illuminate\Http\JsonResponse
    {
        $post = Post::find($postId);
        if ($post === null) {
            return response()->json(['message' => "Le message n'a pas été trouvé"], 404);
        }

        // If user is not the creator of the post and not an admin, return 403
        if ($request->user()->id !== $post->userId && !$request->user()->isAdmin()) {
            return response()->json(['message' => "Vous n'êtes pas autorisé à supprimer ce message"], 403);
        }

        $post->delete();
        return response()->json();
    }

    public function searchInForums(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'search' => 'required|string'
        ]);

        $search = $request->input('search');

        $topics = Topic::search($search)->query(fn (Builder $query) => $query->with('creator'))->get();
        // Adds forumId to the topics
        $topics->each(function ($topic) {
            $topic->forumId = $topic->forum->id;
        });
        $posts = Post::search($search)->query(fn (Builder $query) => $query->with('topic', 'topic.creator', 'user'))->get();

        return response()->json(['topics' => $topics, 'posts' => $posts]);
    }
}
