<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Notifications\NewMessageNotification;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $message = Message::create([
            'user_id' => $request->user()->id,
            'content' => $request->input('content'),
        ]);

        // Notify the user
        $request->user()->notify(new NewMessageNotification($message));

        return response()->json($message, 201);
    }

    public function getMessages(Request $request)
    {
        $messages = Message::where('user_id', $request->user()->id)->get();

        return response()->json($messages);
    }
}
