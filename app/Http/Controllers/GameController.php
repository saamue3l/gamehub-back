<?php

namespace App\Http\Controllers;

use App\Models\FavoriteGame;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller handling game-related operations.
 *
 * This controller manages game search operations and filtering,
 * including searches that exclude games already marked as favorites.
 *
 * @package App\Http\Controllers
**/
class GameController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse
     */
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
