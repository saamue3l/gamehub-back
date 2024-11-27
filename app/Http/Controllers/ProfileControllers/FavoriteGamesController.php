<?php

namespace App\Http\Controllers\ProfileControllers;

use App\Models\FavoriteGame;
use App\Services\SuccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FavoriteGamesController
{
    protected SuccessService $successService;

    public function __construct(SuccessService $successService)
    {
        $this->successService = $successService;
    }

    public function addFavoriteGame(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated'
            ], 401);
        }

        $validatedData = $request->validate([
            'description' => 'required|string|max:255',
            'gameId' => 'required|exists:game,id',
            'skillId' => 'required|exists:skilltype,id',
        ]);

        $favoriteGame = new FavoriteGame();
        $favoriteGame->userId = $user->id;
        $favoriteGame->gameId = $validatedData['gameId'];
        $favoriteGame->skillTypeId = $validatedData['skillId'];
        $favoriteGame->description = $validatedData['description'];
        $favoriteGame->save();

        $result = $this->successService->handleAction($user, 'ADD_GAME');

        return response()->json([
            'favoriteGameId' => $favoriteGame->id,
            'xpGained' => $result['xpGained'],
            'newSuccess' => $result['newSuccess']
        ]);
    }

    public function updateFavoriteGame(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated'
            ], 401);
        }

        Log::info('Received data: ', $request->all());

        $validatedData = $request->validate([
            'id' => 'required|exists:favoriteGame,id',
            'description' => 'required|string|max:255',
            'skill.id' => 'required|exists:skillType,id',
        ]);

        $favoriteGame = FavoriteGame::where('id', $validatedData['id'])->where('userId', $user->id)->first();

        if (!$favoriteGame) {
            return response()->json([
                'status' => 'error',
                'message' => 'Favorite game not found'
            ], 404);
        }

        $favoriteGame->skillTypeId = $validatedData['skill']['id'];
        $favoriteGame->description = $validatedData['description'];
        $favoriteGame->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Favorite game updated successfully',
            'favoriteGame' => $favoriteGame
        ]);
    }

    public function deleteFavoriteGame(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated'
            ], 401);
        }

        Log::info('Received data: ', $request->all());

        $validatedData = $request->validate([
            'favoriteGameId' => 'required|exists:favoriteGame,id',
        ]);

        $favoriteGame = FavoriteGame::where('id', $validatedData['favoriteGameId'])->where('userId', $user->id)->first();

        if (!$favoriteGame) {
            return response()->json([
                'status' => 'error',
                'message' => 'Favorite game not found'
            ], 404);
        }

        $favoriteGame->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Favorite game deleted successfully'
        ]);
    }
}
