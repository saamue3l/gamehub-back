<?php

namespace Database\Factories;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * @return array<string,
     */
    public function definition($userId = null, $topicId = null): array
    {
        $topicId = $topicId ?? Topic::inRandomOrder()->first()->id;
        $topic = Topic::where('id', $topicId)->first(); // Yes I know
        $topicPosts = $topic->posts()->get();
        $topicCreationDate = null;
        if (sizeof($topicPosts) > 0) {
            $topicCreationDate = $topicPosts->where('creationDate', $topicPosts->min('creationDate'))[0]->creationDate;
        }

        return [
            'content' => $this->faker->text(),
            'creationDate' => $this->faker->dateTimeBetween($topicCreationDate ?? "-5 years", 'now'),
            'userId' => $userId ?? User::inRandomOrder()->first()->id,
            'topicId' => $topicId,
        ];
    }
}
