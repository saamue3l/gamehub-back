<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        return [
            'senderId' => User::factory(),
            'recipientId' => User::factory(),
            'content' => $this->faker->text,
        ];
    }
}
