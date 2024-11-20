<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Models\Message;
use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\NewMessageNotification;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class ChatController extends Controller
{

    //note: ici c'est la fonction qui crée l'event de message quand j'utilisais pusher,
    //je laisse ça la on sait jamais
    public function message(Request $request) {

        event(new MessageEvent($request->username, $request->message));


        return [];
    }

    public function sendMessage(Request $request)
    {

        Message::create([
            'senderId' => auth()->id(),
            'recipientId' => $request->input('recipientId'),
            'content' => $request->input('content'),
        ]);

        Notification::create([
            'userId' => $request->input('recipientId'),
            'typeId' => 1,
            'message' => 'You have a new message from ' . $request->user()->username,
            'readAt' => null,
            'processedAt' => null,
        ]);

        return response()->json([
            'status' => 'success',
        ], 201);
    }


    public function getConversationUsers(Request $request)
    {
        $userId = Auth::id();
        $userIds = Message::where('senderId', $userId)
            ->orWhere('recipientId', $userId)
            ->selectRaw('CASE WHEN senderId = ? THEN recipientId ELSE senderId END as user_id', [$userId])
            ->distinct()
            ->pluck('user_id');

        $users = User::whereIn('id', $userIds)->get();

        return response()->json($users);
    }

    public function getMessagesWithUser($userId)
    {
        $currentUserId = Auth::id();

        $messages = Message::where(function($query) use ($currentUserId, $userId) {
            $query->where('senderId', $currentUserId)
                ->where('recipientId', $userId);
        })
            ->orWhere(function($query) use ($currentUserId, $userId) {
                $query->where('senderId', $userId)
                    ->where('recipientId', $currentUserId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function getCurrentUser() {
        $currentUserId = Auth::id();
        return response()->json($currentUserId);
    }
}
