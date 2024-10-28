<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

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
}
