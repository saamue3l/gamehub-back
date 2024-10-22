<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    private string $todaysDate;

    public function __construct($count = null, ?Collection $states = null, ?Collection $has = null, ?Collection $for = null, ?Collection $afterMaking = null, ?Collection $afterCreating = null, $connection = null, ?Collection $recycle = null)
    {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection, $recycle);

        $this->todaysDate = date("d/m/y H:i");
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomFutureDate = strtotime("+".rand(0, 30)." weeks", strtotime($this->todaysDate));
        $randomFutureDate = strtotime("+".rand(1, 7)." days", $randomFutureDate);

        $randomGame = Game::inRandomOrder()->first();
        if (rand(0,1) == 0) {
            $randomGameName = "LAN party " . $randomGame->name;
        }
        else {
            $randomGameName = "Tournoi " . $randomGame->name;
        }

        return [
            'name' => $randomGameName,
            'description' => $this->faker->sentence(),
            'maxPlayers' => rand(2, 50),
            'eventDate' => $randomFutureDate,
            'creatorId' => User::inRandomOrder()->first()->id,
            'gameId' => $randomGame->id,
        ];
    }
}
