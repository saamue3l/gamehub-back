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
            'platformId' => Platform::inRandomOrder()->first()->platformId,  // Récupère un platformId aléatoire
            'skillTypeId' => SkillType::inRandomOrder()->first()->skillTypeId,  // Récupère un skillTypeId aléatoire
            'gameId' => Game::inRandomOrder()->first()->gameId,  // Récupère un gameId aléatoire
            'userId' => User::inRandomOrder()->first()->userId,  // Récupère un userId aléatoire
        ];
    }
}
