<?php

namespace Database\Factories;

use App\Models\FavoriteGame;
use App\Models\SkillType;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavoriteGameFactory extends Factory
{
    protected $model = FavoriteGame::class;

    public function definition()
    {
        return [
            'description' => $this->faker->sentence(),  // Génère une description aléatoire
            'skillTypeId' => SkillType::inRandomOrder()->first()->id,  // Récupère un skillTypeId aléatoire
            'gameId' => $this->faker->numberBetween(1, 50),  // Récupère un gameId aléatoire
            'userId' => $this->faker->numberBetween(1, 10),  // Récupère un userId aléatoire
        ];
    }
}
