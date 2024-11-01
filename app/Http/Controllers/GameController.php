<?php

namespace App\Http\Controllers;

use App\Models\FavoriteGame;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function searchGame(Request $request) {
        if (isset($request->search)) {
            return Game::search($request->search)->get();
        }
        else {
            return response()->json([
                'message' => 'The search must include a body with "search" as key.'
            ], 400);
        }
    }

    public function searchGamesWithoutFavorites(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'User not authenticated'
        ], 401);
    }

    if (!isset($request->search)) {
        return response()->json([
            'message' => 'The search must include a body with "search" as key.'
        ], 400);
    }

    $favoriteGameIds = FavoriteGame::where('userId', $user->id)->pluck('gameId')->toArray();

    $games = Game::search($request->search)
        ->whereNotIn('id', $favoriteGameIds)
        ->get();

    return response()->json($games);
}
}
