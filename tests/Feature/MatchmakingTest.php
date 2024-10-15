<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\FavoriteGame;
use App\Models\Role;
use App\Models\Status;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MatchmakingTest extends TestCase
{

    /** @test */
    public function it_can_match_users_based_on_favorite_games()
    {
        // Utiliser le premier utilisateur par défaut
        $this->user = User::first();
        Sanctum::actingAs($this->user);

        // Préparer les données de la requête
        $requestedGames = [
            ['gameId' => 1, 'platformId' => 1, 'skillTypeId' => 1],
            ['gameId' => 2, 'platformId' => 2, 'skillTypeId' => 2],
        ];

        // Envoyer une requête POST à l'API
        $response = $this->postJson('/api/match', $requestedGames);

        // Afficher la réponse JSON pour déboguer si besoin
        $responseData = json_decode($response->getContent(), true); // Décode le JSON en tableau associatif
        echo json_encode($responseData, JSON_PRETTY_PRINT); // Réencode avec formatage

        // Vérifier le statut de la réponse
        $response->assertStatus(200);

        // Vérifier le statut de la réponse
        $response->assertStatus(200);

        // Vérifier que la réponse contient des données attendues
        $response->assertJsonStructure([
            'data' => [
                '*' => ['userId', 'username', 'picture', 'xp', 'gamesQtyFound'],
            ],
        ]);
    }

}
