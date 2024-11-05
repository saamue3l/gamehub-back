<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\NewMessageNotification;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'recipientId' => 'required|exists:user,id',
        ]);

        Message::create([
            'senderId' => auth()->id(),
            'recipientId' => $request->input('recipientId'),
            'content' => $request->input('content'),
        ]);

        Notification::create([
            'userId' => $request->recipientId,
            'typeId' => 1,
            'message' => 'You have a new message from ' . $request->user()->username,
            'readAt' => null,
            'processedAt' => null,
        ]);

        return response()->json([
            'status' => 'success',
        ], 201);
    }

    public function getMessages(Request $request)
    {
        $messages = Message::where('senderId', $request->user()->id)
            ->orWhere('recipientId', $request->user()->id)
            ->get();

        return response()->json($messages);
    }
}
