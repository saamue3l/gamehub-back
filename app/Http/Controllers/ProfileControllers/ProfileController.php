<?php

namespace App\Http\Controllers\ProfileControllers;

use App\Http\Controllers\Controller;
use App\Models\FavoriteGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class ProfileController extends Controller
{
    /**
     * @param string $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFavoriteGames($username): \Illuminate\Http\JsonResponse
    {
        $user = $this->getUserByUsername($username);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        $favoriteGames = \App\Models\FavoriteGame::where('userId', $user->id)
            ->with(['game', 'skilltype'])
            ->orderBy('id', 'desc')
            ->get();

        $favoriteGamesWithDetails = $favoriteGames->map(function ($favoriteGame) {
            return [
                'id' => $favoriteGame->id,
                'description' => $favoriteGame->description,
                'game' => [
                    'id' => $favoriteGame->game->id,
                    'name' => $favoriteGame->game->name,
                    'cover' => $favoriteGame->game->coverUrl,
                ],
                'skill' => [
                    'id' => $favoriteGame->skilltype->id,
                    'label' => $favoriteGame->skilltype->label,
                ],
            ];
        });

        return response()->json($favoriteGamesWithDetails);
    }

    /**
     * @param string $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserInfo($username): \Illuminate\Http\JsonResponse
    {
        $user = $this->getUserByUsername($username);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        $totalXP = $user->xp;
        if(!$totalXP){
            $level = 1;
            $xp = 0;
        } else {
            $level = floor($totalXP / 100);
            $xp = $totalXP % 100;
        }

        $userInfo = [
            'id' => $user->id,
            'username' => $user->username,
            'picture' => $user->picture ? url('storage/' . $user->picture) : null,
            'level' => $level,
            'totalXp' => $totalXP,
            'xp' => $xp,
        ];

        return response()->json($userInfo);
    }

    /**
     * @param string $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserStats($username): \Illuminate\Http\JsonResponse
    {
        $user = $this->getUserByUsername($username);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        $postCount = $user->posts()
            ->whereNotIn('id', function($query) {
                $query->select('p1.id')
                    ->from('post as p1')
                    ->join('topic', 'topic.id', '=', 'p1.topicId')
                    ->whereRaw('p1.userId = topic.creatorId')
                    ->whereRaw('p1.creationDate = (
                    SELECT MIN(p2.creationDate)
                    FROM post as p2
                    WHERE p2.topicId = p1.topicId
                )');
            })
            ->count();

        $stats = [
            'success' => $user->successes()->count(),
            'createdEvents' => $user->createdEvents()->count(),
            'joinedEvents' => $user->participations()->count(),
            'topic' => $user->createdTopics()->count(),
            'post' => $postCount,
            'reaction' => $user->reactions()->count(),
        ];

        return response()->json($stats);
    }

    /**
     * @param string $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserSuccess($username): \Illuminate\Http\JsonResponse
    {
        $user = $this->getUserByUsername($username);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        $successes = $user->successes()->get();

        $successesWithDetails = $successes->map(function ($success) {
            return [
                'name' => $success->name,
                'description' => $success->description,
            ];
        });

        return response()->json($successesWithDetails);
    }

    /**
     * @param string $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserAlias($username): \Illuminate\Http\JsonResponse
    {
        $user = $this->getUserByUsername($username);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        $alias = \App\Models\Username::where('userId', $user->id)->with(['platform'])->get();

        $aliasWithDetails = $alias->map(function ($alias) {
            return [
                'username' => $alias->username,
                'platform' => [
                    'id' => $alias->platform->id,
                    'name' => $alias->platform->name,
                    'logoUrl' => $alias->platform->logoUrl,
                ],
            ];
        });

        return response()->json($aliasWithDetails);
    }

    /**
     * @param string $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserAvailability($username): \Illuminate\Http\JsonResponse
    {
        $user = $this->getUserByUsername($username);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        $availability = \App\Models\Availability::where('userId', $user->id)->get();

        $availabilityWithDetails = $availability->map(function ($availability) {
            return [
                'dayOfWeek' => $availability->dayOfWeek,
                'morning' => $availability->morning,
                'afternoon' => $availability->afternoon,
                'evening' => $availability->evening,
                'night' => $availability->night,
            ];
        });

        return response()->json($availability);
    }

    /**
     * @param string $username
     * @return mixed
     */
    private function getUserByUsername($username): mixed
    {
        return \App\Models\User::where('username', $username)->first();
    }
}
