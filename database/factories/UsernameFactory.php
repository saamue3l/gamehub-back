<?php

namespace Database\Factories;

use App\Models\Platform;
use App\Models\Username;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsernameFactory extends Factory
{
    protected $model = Username::class;

    public function definition()
    {
        return [
            'username' => $this->faker->userName,  // Génère un pseudo aléatoire
            'platformId' => Platform::inRandomOrder()->first()->id,  // Récupère un platformId aléatoire
            'userId' => $this->faker->numberBetween(1, 10),  // Récupère un userId aléatoire
        ];
    }
}
