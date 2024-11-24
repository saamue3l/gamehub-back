<?php

namespace Database\Factories;

use App\Models\Forum;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Topic>
 */
class TopicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'forumId' => Forum::inRandomOrder()->first()->id,
            'creatorId' => User::inRandomOrder()->first()->id,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Topic $topic) {
            // Make the first post from the creator
            Post::create(PostFactory::new()->definition($topic->creatorId, $topic->id));
        });
    }
}
