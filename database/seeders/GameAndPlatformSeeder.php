<?php

namespace Database\Seeders;

use App\Models\Game;
use Database\Factories\RealMultiplayerGames\GamesCsvReader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GameAndPlatformSeeder extends Seeder
{
    protected GamesCsvReader $reader;

    public function __construct()
    {
        $this->reader = new GamesCsvReader();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $factory = Game::factory();
            // Get the number of games in the CSV
            $csvGamesCount = $factory->gamesCount();

            // Determine how many games to create based on environment
            $count = $this->getGameCount($csvGamesCount);

            $factory->count($count)->create();

        } catch (\Exception $e) {
            Log::error('Error during game seeding: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            if (app()->environment('local', 'development')) {
                throw $e; // Re throw in development for better debugging
            }
        }
    }

    /**
     * Determine how many games to create based on environment variables
     */
    protected function getGameCount(int $csvGamesCount): int
    {
        $count = match (strtolower(app()->environment())) {
            'production' => config('seeding.games.production_count') ??  $csvGamesCount,
            default => config('seeding.games.local_count') ??  max(30, $csvGamesCount),
        };

        // Ensure we don't try to create more games than we have in the CSV
        return min($count, $csvGamesCount);
    }
}
