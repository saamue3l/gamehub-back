<?php

namespace Database\Factories;

// database/factories/MessageFactory.php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        // Récupère deux utilisateurs différents pour sender et recipient
        $sender = User::inRandomOrder()->first();
        $recipient = User::where('id', '!=', $sender->id)->inRandomOrder()->first();

        return [
            'senderId' => $sender->id,
            'recipientId' => $recipient->id,
            'content' => $this->faker->sentence(),
            'created_at' => $this->faker->dateTimeBetween('-1 years', 'now'),
        ];
    }
}

