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

        // Récupérer les utilisateurs avec le dernier message
        $userConversations = Message::where(function ($query) use ($userId) {
            $query->where('senderId', $userId)
                ->orWhere('recipientId', $userId);
        })
            ->with(['sender', 'recipient']) // Charger les relations des utilisateurs
            ->get()
            ->groupBy(function ($message) use ($userId) {
                // Grouper par l'autre utilisateur dans la conversation
                return $message->senderId === $userId ? $message->recipientId : $message->senderId;
            })
            ->map(function ($messages) {
                // Trouver le message le plus récent pour chaque groupe
                return $messages->sortByDesc('created_at')->first();
            })
            ->sortByDesc('created_at'); // Trier par date du dernier message

        // Récupérer les utilisateurs associés
        $users = $userConversations->map(function ($message) use ($userId) {
            $user = $message->senderId === $userId ? $message->recipient : $message->sender;
            $user->last_message_date = $message->created_at;
            return $user;
        });

        // Retourner la liste triée
        return response()->json($users->values());
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
