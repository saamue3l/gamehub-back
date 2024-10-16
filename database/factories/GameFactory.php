<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Platform;
use Database\Factories\RealMultiplayerGames\GamesCsvReader;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{

    protected GamesCsvReader $reader;
    protected array $games;
    private int $currentGameIndex = 0;

    public function __construct($count = null, ?Collection $states = null, ?Collection $has = null, ?Collection $for = null, ?Collection $afterMaking = null, ?Collection $afterCreating = null, $connection = null, ?Collection $recycle = null)
    {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection, $recycle);
        $this->reader = new GamesCsvReader();
        $this->games = $this->reader->getGames();

        // Missing data prints
        /*print("Unnamed platforms");
        print_r($this->reader->getUnnamedPlatforms());
        print("unpictured platforms");
        print_r($this->reader->getUnpicturedPlatforms());*/
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if ($this->currentGameIndex > sizeof($this->games)) {
            throw new \Exception("No more games 🤨");
        }

        $game = $this->games[$this->currentGameIndex];
        $this->currentGameIndex++;

        return [
            'name' => $game->name,
            'coverUrl' => $game->coverUrl,
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Game $game) {
            $this->attachPlatforms($game);
        });
    }

    /**
     * Attach platforms to the game
     */
    protected function attachPlatforms(Game $game): void
    {
        $csvGame = $this->games[$game->id -1]; // We use id as $currentGameIndex is still at 0
        foreach ($csvGame->platforms as $platformData) {
            try {
                if (empty($platformData->name) || empty($platformData->imageUrl)) {
                    \Log::warning("Skipping platform with empty name for game {$game->name}");
                    continue;
                }

                $platform = Platform::firstOrCreate(
                    ['name' => $platformData->name],
                    ['logoUrl' => $platformData->imageUrl]
                );

                if ($platform->id) {
                    $game->platforms()->attach($platform->id);
                }
            } catch (\Exception $e) {
                \Log::error("Error attaching platform to game {$game->name}: " . $e->getMessage(), [
                    'platform' => $platformData,
                    'game' => $game->toArray()
                ]);
            }
        }
    }

    public function gamesCount(): int
    {
        return sizeof($this->games);
    }
}
