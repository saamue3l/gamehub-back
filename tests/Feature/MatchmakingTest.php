<?php

namespace Tests\Feature;

use Mockery;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MatchmakingTest extends TestCase
{

    /** @test */
    public function it_can_match_users_based_on_favorite_games()
    {
        // Créer un utilisateur et associer des jeux favoris
        $user = User::factory()->create();

        // Utiliser Sanctum pour authentifier l'utilisateur
        Sanctum::actingAs($user);

        // Préparer les données de la requête
        $requestedGames = [
            ['gameId' => 1, 'platformId' => 1, 'skillTypeId' => 1],
            ['gameId' => 2, 'platformId' => 2, 'skillTypeId' => 2],
        ];

        // Simuler la réponse
        $response = $this->postJson('/api/match', $requestedGames);

        // Vérifier le statut de la réponse
        $response->assertStatus(200);

        // Vérifier que la réponse contient les informations attendues
        $response->assertJsonStructure([
            'matchResult' => [
                '*' => ['userId', 'username', 'picture', 'xp', 'gamesQtyFound'],
            ],
        ]);
    }



    /** @test */
    public function it_fails_when_user_is_not_authenticated()
    {
        // On envoie la requête sans authentifier l'utilisateur
        $response = $this->postJson('/api/match', [
            'requestedGames' => [
                ['gameId' => 1, 'platformId' => 1, 'skillTypeId' => 1],
            ],
        ]);

        // Vérifier que la réponse retourne une erreur d'authentification
        $response->assertStatus(401);
    }

    /** @test */
    /*public function it_can_match_users_based_on_favorite_games()
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
        //$responseData = json_decode($response->getContent(), true); // Décode le JSON en tableau associatif
        //echo json_encode($responseData, JSON_PRETTY_PRINT); // Réencode avec formatage

        // Vérifier le statut de la réponse
        $response->assertStatus(200);

        // Vérifier que la réponse contient des données attendues
        $response->assertJsonStructure([
            'matchResult' => [
                '*' => ['userId', 'username', 'picture', 'xp', 'gamesQtyFound'],
            ],
        ]);
    }*/

    /** @test */
    public function it_fails_when_required_data_is_empty()
    {
        // Utiliser le premier utilisateur par défaut
        $this->user = User::first();
        Sanctum::actingAs($this->user);

        $requestedGames = [
            ['gameId' => 1],
        ];

        // Envoyer une requête POST à l'API
        $response = $this->postJson('/api/match', $requestedGames);

        // Vérifier que la réponse retourne une erreur de validation (status 422)
        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            '0.platformId',  // Correspond à l'index '0' pour platformId
            '0.skillTypeId', // Correspond à l'index '0' pour skillTypeId
        ]);
    }

    /** @test */
    public function it_fails_when_required_data_is_empty_on_several_games()
    {
        // Utiliser le premier utilisateur par défaut
        $this->user = User::first();
        Sanctum::actingAs($this->user);

        $requestedGames = [
            ['gameId' => 1, 'platformId' => 1, 'skillTypeId' => 1],
            ['gameId' => 2],
        ];

        // Envoyer une requête POST à l'API
        $response = $this->postJson('/api/match', $requestedGames);

        // Vérifier que la réponse retourne une erreur de validation (status 422)
        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            '1.platformId',  // Correspond à l'index '1' pour platformId
            '1.skillTypeId', // Correspond à l'index '1' pour skillTypeId
        ]);
    }

    /** @test */
    public function it_fails_when_no_array()
    {
        // Utiliser le premier utilisateur par défaut
        $this->user = User::first();
        Sanctum::actingAs($this->user);

        $requestedGames = [];

        // Envoyer une requête POST à l'API
        $response = $this->postJson('/api/match', $requestedGames);

        // Vérifier que la réponse retourne une erreur de validation (status 422)
        $response->assertStatus(422);

        // Vérifier l'erreur liée à l'absence du tableau
        $response->assertJsonValidationErrors([
            'requestedGames' => 'The requestedGames array is required.'
        ]);
    }

    /** @test */
    public function it_fails_when_required_data_is_missing()
    {
        // Utiliser le premier utilisateur par défaut
        $this->user = User::first();
        Sanctum::actingAs($this->user);

        $requestedGames = [
            [],
        ];

        // Envoyer une requête POST à l'API
        $response = $this->postJson('/api/match', $requestedGames);

        // Vérifier que la réponse retourne une erreur de validation (status 422)
        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            '0.gameId',     // Correspond à l'index '0' pour gameId
            '0.platformId',  // Correspond à l'index '0' pour platformId
            '0.skillTypeId', // Correspond à l'index '0' pour skillTypeId
        ]);
    }
}
