<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reaction;
use App\Models\ReactionType;
use App\Models\UserFirstReaction;
use App\Services\SuccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class ReactionController extends Controller
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
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function reactToPost(Request $request, Post $post): \Illuminate\Http\JsonResponse
    {
        /* Get the emoji */
        if (!$request->filled("emoji")) {
            return response()->json(["message" => "Must include which emoji the user reacted with"], 400);
        }

        $emoji = $request->input('emoji');
        $reactionType = ReactionType::where('emoji', '=', $emoji)->first();
        if ($reactionType === null) {
            return response()->json(["message" => "Invalid emoji"], 400);
        }

        $user = $request->user();
        $currentReactedStatusQuery = Reaction::where([
            ['userId', $user->id],
            ['postId', $post->id],
            ['reactionTypeId', $reactionType->id]
        ]);
        $currentReactedStatusExists = $currentReactedStatusQuery->exists();

        // Vérifier si c'est la première réaction de l'utilisateur pour ce post
        $isFirstReaction = !UserFirstReaction::where([
            'userId' => $user->id,
            'postId' => $post->id
        ])->exists();

        if (!$currentReactedStatusExists) { // Did not already reacted
            try {
                $post->reactToPost($user, $reactionType);
            }
            catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }

                $result = [
                'xpGained' => null,
                'newSuccess' => null
            ];

            if ($isFirstReaction) {
                $result = $this->successService->handleAction($user, 'REACT_TO_MESSAGE');

                        UserFirstReaction::create([
                    'userId' => $user->id,
                    'postId' => $post->id
                ]);
            }
        }
        else { // Remove the reaction
            $currentReactedStatusQuery->delete();

            $result = [
                'xpGained' => null,
                'newSuccess' => null
            ];
        }

        return response()->json([
            'userReacted' => !$currentReactedStatusExists,
            'xpGained' => $result['xpGained'],
            'newSuccess' => $result['newSuccess']
        ]);
    }

    /**
     * @return array
     */
    private function getAvailableReactionTypes(): array
    {
        return Cache::remember('reactionTypes', 60, function () {
            return ReactionType::pluck('emoji')->all();
        });
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllReactionTypes(Request $request): \Illuminate\Http\JsonResponse
    {
        $reactionTypes = $this->getAvailableReactionTypes();
        return response()->json($reactionTypes)
            ->header('Cache-Control', 'public, max-age=3600')
            ->setEtag(count($reactionTypes));
    }
}
