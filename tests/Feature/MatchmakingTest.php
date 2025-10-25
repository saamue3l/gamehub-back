<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\FavoriteGame;
use App\Models\Game;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

class MatchmakingTest extends TestCase
{
    use DatabaseMigrations;

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed', ['--class' => 'StatusAndRoleSeeder']); // Appeler le seeder pour les rôles et statuts
        Artisan::call('db:seed', ['--class' => 'GameSeeder']); // Appeler le seeder pour les jeux
        Artisan::call('db:seed', ['--class' => 'SkillSeeder']); // Appeler le seeder pour les types de compétence
        Artisan::call('db:seed', ['--class' => 'UserSeeder']); // Appeler le seeder pour les utilisateurs

        $this->assignFavoriteGames();
    }


    protected function assignFavoriteGames(): void
    {
        foreach (User::take(5)->get() as $user) {
            $favoriteGame = new FavoriteGame;
            $favoriteGame->userId = $user->id;
            $favoriteGame->skillTypeId = 1;
            $favoriteGame->gameId = $user->id;
            $favoriteGame->save();

            $favoriteGame = new FavoriteGame;
            $favoriteGame->userId = $user->id;
            $favoriteGame->skillTypeId = 2;
            $favoriteGame->gameId = $user->id+5;
            $favoriteGame->save();
        }
    }

    /** @test */
    public function it_fails_when_user_is_not_authenticated()
    {
        $response = $this->postJson('/api/matchmaking', [
            'requestedGames' => [
                ['gameId' => 1, 'skillTypeId' => 1],
            ],
        ]);

        // Vérifier que la réponse retourne une erreur d'authentification
        $response->assertStatus(401);
    }

    /** @test */
    public function it_can_return_specific_json_structure()
    {
        $user = User::first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $token = $user->createToken('TestToken')->plainTextToken;

        $requestedGames = [
            ['gameId' => 1, 'skillTypeId' => 1],
            ['gameId' => 2, 'skillTypeId' => 2],
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/matchmaking', $requestedGames);

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
        //Artisan::call('db:seed', ['--class' => 'GameSeeder']); // Appeler le seeder pour les jeux
        //Artisan::call('db:seed', ['--class' => 'UserSeeder']); // Appeler le seeder pour les utilisateurs

        $user = User::find(4);
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $token = $user->createToken('TestToken')->plainTextToken;

        $requestedGames = [
            ['gameId' => 1, 'skillTypeId' => 1], //user 1
            ['gameId' => 6, 'skillTypeId' => 2], //user 1
            ['gameId' => 2, 'skillTypeId' => 1], //user 2
            ['gameId' => 8, 'skillTypeId' => 2], //user 3
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/matchmaking', $requestedGames);

        // Vérifier le statut de la réponse
        $response->assertStatus(200);

        //$responseData = json_decode($response->getContent(), true); // Décode le JSON en tableau associatif
        //echo json_encode($responseData, JSON_PRETTY_PRINT); // Réencode avec formatage

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
        $user = User::first();
        Sanctum::actingAs(
            $user,
            ['*']
        );

        $token = $user->createToken('TestToken')->plainTextToken;

        $requestedGames = [
            ['gameId' => 1],
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/matchmaking', $requestedGames);

        // Vérifier que la réponse retourne une erreur de validation (status 422)
        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            '0.skillTypeId', // Correspond à l'index '0' pour skillTypeId
        ]);
    }

    /** @test */
    public function it_fails_when_required_data_is_empty_on_several_games()
    {
        $user = User::first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $token = $user->createToken('TestToken')->plainTextToken;

        $requestedGames = [
            ['gameId' => 1, 'skillTypeId' => 1],
            ['gameId' => 2],
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/matchmaking', $requestedGames);

        // Vérifier que la réponse retourne une erreur de validation (status 422)
        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            '1.skillTypeId', // Correspond à l'index '1' pour skillTypeId
        ]);
    }

    /** @test */
    public function it_fails_when_no_array()
    {
        $user = User::first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $token = $user->createToken('TestToken')->plainTextToken;

        $requestedGames = [];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/matchmaking', $requestedGames);

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
        $user = User::first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $token = $user->createToken('TestToken')->plainTextToken;

        $requestedGames = [
            [],
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/matchmaking', $requestedGames);

        // Vérifier que la réponse retourne une erreur de validation (status 422)
        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            '0.gameId',     // Correspond à l'index '0' pour gameId
            '0.skillTypeId', // Correspond à l'index '0' pour skillTypeId
        ]);
    }
}
