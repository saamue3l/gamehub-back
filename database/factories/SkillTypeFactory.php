<?php

namespace Database\Factories;

use App\Models\SkillType;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillTypeFactory extends Factory
{
    protected $model = SkillType::class;

    public function definition()
    {
        return [
            'label' => $this->faker->word(),  // Génère un label aléatoire si besoin, mais pas utilisé dans le seeder
        ];
    }
}
