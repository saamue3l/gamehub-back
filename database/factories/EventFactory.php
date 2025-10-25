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

        $this->todaysDate = date("c");
    }

    /**
     * @return array<string,
     */
    public function definition(): array
    {
        $randomFutureDate = strtotime("+".rand(0, 30)." weeks", strtotime($this->todaysDate));
        $randomFutureDate = strtotime("+".rand(1, 7)." days", $randomFutureDate);

        $randomNameTitle = ["LAN party", "Tournoi", "Rassemblement", "Rencontre", "Compétition", "Match", "Défi", "Rasso", "RP", "Roleplay", "Soirée", "Soirée jeu"];

        $randomGame = Game::inRandomOrder()->first();
        $randomName = $randomNameTitle[rand(0, sizeof($randomNameTitle)-1)] . " " . $randomGame->name;

        return [
            'name' => $randomName,
            'description' => $this->faker->sentence(rand(5, 50)),
            'maxPlayers' => rand(2, 20),
            'eventDate' => $randomFutureDate,
            'creatorId' => User::inRandomOrder()->first()->id,
            'gameId' => $randomGame->id,
        ];
    }
}
