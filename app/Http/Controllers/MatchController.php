<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatchController extends Controller
{
    public function match(Request $request)
    {
        // Valider que le tableau des jeux est bien présent et correctement formaté
        $validated = $request->validate([
            '*.gameId' => 'required|integer',
            '*.skillTypeId' => 'required|integer',
        ]);

        // Si le tableau des jeux est vide
        if (empty($validated)) {
            return response()->json([
                'message' => 'The requestedGames array must contain at least one game.',
                'errors' => ['requestedGames' => ['The requestedGames array is required.']],
            ], 422);
        }

        // Récupérer les jeux demandés
        $requestedGames = $validated;

        // Récupérer les utilisateurs avec le compte des jeux correspondants
        $users = User::where('statusId', 1) // Filtrer les utilisateurs actifs (non bannis)
        ->whereHas('favoriteGames', function($query) use ($requestedGames) {
            $query->where(function ($q) use ($requestedGames) {
                foreach ($requestedGames as $game) {
                    $q->orWhere(function ($subQuery) use ($game) {
                        $subQuery->where('gameId', $game['gameId'])
                            ->where('skillTypeId', $game['skillTypeId']);
                    });
                }
            });
        })
            ->with(['favoriteGames' => function($query) use ($requestedGames) {
                $query->where(function ($q) use ($requestedGames) {
                    foreach ($requestedGames as $game) {
                        $q->orWhere(function ($subQuery) use ($game) {
                            $subQuery->where('gameId', $game['gameId'])
                                ->where('skillTypeId', $game['skillTypeId']);
                        });
                    }
                });
            }])
            ->get()
            ->map(function ($user) {
                // Compter le nombre de jeux correspondants pour chaque utilisateur
                return [
                    'userId' => $user->id,
                    'username' => $user->username,
                    'xp' => $user->xp,
                    'picture' => $user->picture,
                    'gamesQtyFound' => $user->favoriteGames->count(), // Compter les jeux favoris
                ];
            });

        // Si aucun utilisateur n'a été trouvé
        if ($users->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No users found matching the criteria.',
                'matchResult' => [],
            ], 200);
        }

        // Retourner les résultats
        return response()->json([
            'status' => 'success',
            'matchResult' => $users
        ]);
    }
}
