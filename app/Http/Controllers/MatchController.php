<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;



class MatchController extends Controller
{
    /**
     * Finds matching players based on game preferences and skill types.
     *
     * Matches users based on their game preferences and skill types, excluding the
     * requesting user. Results are sorted by the number of matching games.
     * Only active users (statusId = 1) are included in the results.
     *
     * @param Request $request The HTTP request containing game preferences
     * @return \Illuminate\Http\JsonResponse
     *     - 200: Successful matching with results or empty array
     *     - 422: Invalid request format
     * **/
    public function match(Request $request): \Illuminate\Http\JsonResponse
    {
        //\Log::info('Authorization Header', ['header' => $request->header('Authorization')]);
        $validated = $request->validate([
            '*.gameId' => 'required|integer',
            '*.skillTypeId' => 'required|integer',
        ]);

        if (empty($validated)) {
            return response()->json([
                'message' => 'The requestedGames array must contain at least one game.',
                'errors' => ['requestedGames' => ['The requestedGames array is required.']],
            ], 422);
        }

        $requestedGames = collect($validated);

        // Récupérer l'ID de l'user pour enlever ça des résultats
        $currentUserId = $request->user()->id;

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
                    'picture' => $user->picture ? url('storage/' . $user->picture) : null,
                    'gamesQtyFound' => $user->favoriteGames->count(), // Compter les jeux favoris
                ];
            })
            ->filter(function ($user) use ($currentUserId) {
                return $user['userId'] !== $currentUserId;
            })
            ->sortByDesc('gamesQtyFound')
            ->values();

        if ($users->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No users found matching the criteria.',
                'matchResult' => [],
            ], );
        }

        return response()->json([
            'status' => 'success',
            'matchResult' => $users
        ]);
    }
}
