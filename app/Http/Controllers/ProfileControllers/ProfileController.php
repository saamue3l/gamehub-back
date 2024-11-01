<?php

namespace App\Http\Controllers\ProfileControllers;

use App\Http\Controllers\Controller;
use App\Models\FavoriteGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
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
            'username' => $user->username,
            'picture' => $user->picture,
            'level' => $level,
            'xp' => $xp,
        ];

        return response()->json($userInfo);
    }

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
     * @param $username
     * @return mixed
     */
    private function getUserByUsername($username): mixed
    {
        return \App\Models\User::where('username', $username)->first();
    }
}
