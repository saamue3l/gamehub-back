<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class NewMessageNotification implements ShouldBroadcastNow
{
    use SerializesModels;

    public $userId; // ID de l'utilisateur à notifier
    public $conversationId; // ID de la conversation
    public $unreadCount; // Nombre de messages non lus

    public function __construct($userId, $conversationId, $unreadCount)
    {
        $this->userId = $userId;
        $this->conversationId = $conversationId;
        $this->unreadCount = $unreadCount;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('notifications.' . $this->userId);
    }

    public function broadcastWith()
    {
        return [
            'conversationId' => $this->conversationId,
            'unreadCount' => $this->unreadCount,
        ];
    }
}
