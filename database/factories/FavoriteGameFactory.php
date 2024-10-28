<?php

namespace Database\Factories;

use App\Models\FavoriteGame;
use App\Models\Game;
use App\Models\Platform;
use App\Models\SkillType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavoriteGameFactory extends Factory
{
    protected $model = FavoriteGame::class;

    public function definition()
    {
        return [
            'description' => $this->faker->sentence(),  // Génère une description aléatoire
            'skillTypeId' => SkillType::inRandomOrder()->first()->id,  // Récupère un skillTypeId aléatoire
            'gameId' => Game::inRandomOrder()->first()->id,  // Récupère un gameId aléatoire
            'userId' => User::inRandomOrder()->first()->id,  // Récupère un userId aléatoire
        ];
    }
}
