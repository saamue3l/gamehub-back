<?php

namespace Tests\Feature;

use App\Models\FavoriteGame;
use App\Models\Game;
use App\Models\Role;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Mockery;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MatchmakingTest extends TestCase
{

    use RefreshDatabase;
    protected $users;
    protected $games;

    protected function setUp(): void
    {
        parent::setUp();

        // Exécuter les seeders pour peupler la base de données
        Artisan::call('db:seed', ['--class' => 'StatusAndRoleSeeder']); // Appeler le seeder pour les rôles et statuts
        Artisan::call('db:seed', ['--class' => 'GameSeeder']); // Appeler le seeder pour les jeux
        Artisan::call('db:seed', ['--class' => 'PlatformSeeder']); // Appeler le seeder pour les plateformes
        Artisan::call('db:seed', ['--class' => 'SkillSeeder']); // Appeler le seeder pour les types de compétence
        Artisan::call('db:seed', ['--class' => 'UserSeeder']); // Appeler le seeder pour les utilisateurs
        //Artisan::call('db:seed', ['--class' => 'GameAndPlatformSeeder']); // Appeler le seeder pour les jeux

        // Récupérer les utilisateurs et les jeux après le seeding
        $this->users = User::all(); // Récupère tous les utilisateurs
        $this->games = Game::all(); // Récupère tous les jeux

        // Associer des jeux favoris à des utilisateurs de manière prédictible
        $this->assignFavoriteGames();
    }

    protected function assignFavoriteGames(): void
    {
        // Utilisateurs 1 à 5 auront des jeux favoris spécifiques (1&6, 2&7, 5&8, 4&9, 5&10)
        foreach ($this->users->take(5) as $index => $user) {
            $favoriteGame = new FavoriteGame;
            $favoriteGame->userId = $user->id;
            $favoriteGame->platformId = 1;
            $favoriteGame->skillTypeId = 1;
            $favoriteGame->gameId = $this->games[$index]->id;
            $favoriteGame->save();

            $favoriteGame = new FavoriteGame;
            $favoriteGame->userId = $user->id;
            $favoriteGame->platformId = 2;
            $favoriteGame->skillTypeId = 2;
            $favoriteGame->gameId = $this->games[$index + 5]->id;
            $favoriteGame->save();
        }
    }

    /** @test */
    public function it_fails_when_user_is_not_authenticated()
    {
        // On envoie la requête sans authentifier l'utilisateur
        $response = $this->postJson('/api/matchmaking', [
            'requestedGames' => [
                ['gameId' => 1, 'platformId' => 1, 'skillTypeId' => 1],
            ],
        ]);

        // Vérifier que la réponse retourne une erreur d'authentification
        $response->assertStatus(401);
    }

    /** @test */
    public function it_can_return_specific_json_structure()
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
        $response = $this->postJson('/api/matchmaking', $requestedGames);

        // Vérifier le statut de la réponse
        $response->assertStatus(200);

        // Vérifier que la réponse contient des données attendues
        $response->assertJsonStructure([
            'matchResult' => [
                '*' => ['userId', 'username', 'picture', 'xp', 'gamesQtyFound'],
            ],
        ]);
    }

    /** @test */
    public function it_can_retrieve_specific_users()
    {
        // Créer un utilisateur et associer des jeux favoris
        $user = User::factory()->create();

        // Utiliser Sanctum pour authentifier l'utilisateur
        Sanctum::actingAs($user);

        // Préparer les données de la requête
        $requestedGames = [
            ['gameId' => 1, 'platformId' => 1, 'skillTypeId' => 1],
            ['gameId' => 6, 'platformId' => 2, 'skillTypeId' => 2],
            ['gameId' => 2, 'platformId' => 1, 'skillTypeId' => 1],
            ['gameId' => 8, 'platformId' => 2, 'skillTypeId' => 2],
        ];

        // Simuler la réponse
        $response = $this->postJson('/api/matchmaking', $requestedGames);

        // Vérifier le statut de la réponse
        $response->assertStatus(200);

        // Afficher la réponse JSON pour déboguer si besoin
        $responseData = json_decode($response->getContent(), true); // Décode le JSON en tableau associatif
        echo json_encode($responseData, JSON_PRETTY_PRINT); // Réencode avec formatage

        // Vérifier la structure JSON
        $response->assertJsonStructure([
            'status',
            'matchResult' => [
                '*' => [ // Le * indique qu'il peut y avoir plusieurs éléments dans le tableau
                    'userId',
                    'username',
                    'xp',
                    'picture',
                    'gamesQtyFound',
                ],
            ],
        ]);

        // Vérifier que la réponse contient les informations attendues
        $response->assertJsonFragment([
            'userId' => 1,
            'gamesQtyFound' => 2,
        ]);

        $response->assertJsonFragment([
            'userId' => 2,
            'gamesQtyFound' => 1,
        ]);

        $response->assertJsonFragment([
            'userId' => 3,
            'gamesQtyFound' => 1,
        ]);
    }

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
        $response = $this->postJson('/api/matchmaking', $requestedGames);

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
        $response = $this->postJson('/api/matchmaking', $requestedGames);

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
        $response = $this->postJson('/api/matchmaking', $requestedGames);

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
        $response = $this->postJson('/api/matchmaking', $requestedGames);

        // Vérifier que la réponse retourne une erreur de validation (status 422)
        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            '0.gameId',     // Correspond à l'index '0' pour gameId
            '0.platformId',  // Correspond à l'index '0' pour platformId
            '0.skillTypeId', // Correspond à l'index '0' pour skillTypeId
        ]);
    }
}
