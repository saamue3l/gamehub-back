<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
use InteractsWithSockets, SerializesModels;

public $message;

public function __construct(Message $message)
{
$this->message = $message;
}

public function broadcastOn()
{
// Diffuser uniquement aux utilisateurs de cette conversation
return new PrivateChannel('conversation.' . $this->message->conversationId);
}

public function broadcastAs()
{
return 'message.sent';
}
}
