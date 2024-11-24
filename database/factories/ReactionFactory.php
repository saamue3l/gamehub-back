<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\ReactionType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reaction>
 */
class ReactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'userId' => User::inRandomOrder()->first()->id,
            'reactionTypeId' => ReactionType::inRandomOrder()->first()->id,
            'postId' => Post::inRandomOrder()->first()->id,
        ];
    }
}
