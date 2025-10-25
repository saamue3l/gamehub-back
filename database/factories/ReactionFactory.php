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
     * @return array<string,
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
