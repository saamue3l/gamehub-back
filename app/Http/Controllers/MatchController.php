<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function match(Request $request)
    {
        //\Log::info('Authorization Header', ['header' => $request->header('Authorization')]);
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

        // Extraire les jeux demandés
        $requestedGames = collect($validated);

        // Récupérer les utilisateurs avec les jeux correspondants
        $users = User::where('statusId', 1) // Filtrer les utilisateurs actifs (non bannis)
        ->whereHas('favoriteGames', function($query) use ($requestedGames) {
            $query->whereIn('gameId', $requestedGames->pluck('gameId'))
                ->whereIn('skillTypeId', $requestedGames->pluck('skillTypeId'));
        })
            ->with(['favoriteGames' => function($query) use ($requestedGames) {
                $query->whereIn('gameId', $requestedGames->pluck('gameId'))
                    ->whereIn('skillTypeId', $requestedGames->pluck('skillTypeId'));
            }])
            ->get(['id', 'username', 'xp', 'picture']) // Charger uniquement les champs nécessaires
            ->map(function ($user) use ($requestedGames) {
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
