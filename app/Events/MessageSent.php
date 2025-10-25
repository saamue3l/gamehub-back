<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $type;
    public $conversationId;
    public $recipientId;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->type = $data['type'];
        $this->conversationId = $data['conversationId'];
        Log::info('MessageSent event created with type: ' . $this->type . ' and conversationId: ' . $this->conversationId);
        $this->recipientId = $data['recipientId'];
        Log::info('MessageSent event created with recipientId: ' . $this->recipientId);
    }

    /**
     * @return Channel
     */
    public function broadcastOn()
    {
        $channelName = 'user.' . $this->recipientId;
        Log::info('Broadcasting on channel: ' . $channelName . ' with type: ' . $this->type . ' and conversationId: ' . $this->conversationId);
        return new Channel($channelName);
    }

    /**
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'type' => $this->type,
            'conversationId' => $this->conversationId,
        ];
    }
}
