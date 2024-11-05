<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Message;
use App\Models\Notification;
use App\Models\NotificationType;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LivechatTest extends TestCase
{
    use DatabaseMigrations;

    public function testSendMessage()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();
        $this->actingAs($sender, 'sanctum');

        // Seed the notification types
        NotificationType::create(['name' => 'NewMessageNotification']);

        $response = $this->postJson('/api/messages', [
            'content' => 'Hello, world!',
            'recipientId' => $recipient->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('messages', [
            'senderId' => $sender->id,
            'recipientId' => $recipient->id,
            'content' => 'Hello, world!',
        ]);

        $this->assertDatabaseHas('notifications', [
            'userId' => $recipient->id,
            'message' => 'You have a new message from ' . $sender->username,
        ]);
    }
}
