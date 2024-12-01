<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConversationController extends Controller
{

    public function createConversation(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array|min:2', // Au moins deux utilisateurs dans la conversation
            'user_ids.*' => 'exists:user,id', // Validation des ID
        ]);

        $userIds = collect($request->user_ids)->sort()->values(); // Trie les ID des utilisateurs pour garantir une comparaison correcte

        // Vérifier si une conversation existe déjà pour ces utilisateurs
        $existingConversation = Conversation::whereHas('users', function($query) use ($userIds) {
            $query->whereIn('userId', $userIds);
        })
            ->with('users') // Charger les utilisateurs associés pour vérifier s'ils sont tous présents
            ->get()
            ->first(function ($conversation) use ($userIds) {
                // Vérifie si tous les utilisateurs de user_ids sont dans la conversation
                $conversationUserIds = $conversation->users->pluck('id')->sort()->values();

                // Utilisation de diff pour comparer les collections
                return $conversationUserIds->diff($userIds)->isEmpty();
            });

        // Si une conversation existe déjà, retourne son ID
        if ($existingConversation) {
            return response()->json(['conversationId' => $existingConversation->id]);
        }

        // Si aucune conversation n'existe, en créer une nouvelle
        $conversation = Conversation::create(); // Crée une conversation vide sans nom (vous pouvez ajouter un nom si nécessaire)

        // Associe les utilisateurs à la nouvelle conversation
        $conversation->users()->sync($userIds); // La méthode sync associe les utilisateurs à la conversation

        return response()->json(['conversationId' => $conversation->id], 201);
    }


    /*
    public function getUserConversations(Request $request)
    {
        $user = $request->user();

        $conversations = $user->conversations()->with('users', 'messages.sender')->get();

        return response()->json($conversations);
    }
    */

    public function getUserConversations()
    {
        // Utiliser l'ID de l'utilisateur authentifié
        $userId = auth()->id();

        // Récupérer les conversations de l'utilisateur
        $conversations = Conversation::whereHas('users', function ($query) use ($userId) {
            $query->where('userId', $userId); // Vérifie que l'utilisateur fait partie de la conversation
        })
            ->with('users') // Charger les utilisateurs associés à la conversation
            ->orderBy('updated_at', 'desc') // Trier par la date de la dernière mise à jour
            ->get();

        // Transformer les données pour le front
        $formattedConversations = $conversations->map(function ($conversation) use ($userId) {
            // Récupérer les utilisateurs sauf l'utilisateur authentifié
            $participants = $conversation->users->where('id', '!=', $userId);

            // Construire le nom des participants
            $participantNames = $participants->pluck('username')->implode(', ');

            // Déterminer l'image :
            // - Si un seul participant, utiliser son champ 'picture'
            // - Sinon, utiliser une image générique
            $picture = $participants->count() === 1
                ? $participants->first()->picture ? url('storage/' . $participants->first()->picture) : null // Image du seul participant
                : "https://png.pngtree.com/png-vector/20191009/ourlarge/pngtree-group-icon-png-image_1796653.jpg";


            return [
                'conversationId' => $conversation->id, // ID de la conversation
                'username' => $participantNames,          // Liste des noms des participants
                'picture' => $picture,
                'unreadMessages' => $this->getUnreadMessagesCount($conversation->id)// Image de profil ou générique
            ];
        });

        return response()->json($formattedConversations);
    }



    public function getConversationMessages($conversationId)
    {
        // Utiliser l'ID de l'utilisateur authentifié
        $userId = auth()->id();

        // Vérifier que l'utilisateur fait partie de la conversation
        $conversation = Conversation::whereHas('users', function ($query) use ($userId) {
            $query->where('userId', $userId); // Vérifie l'appartenance
        })
            ->with(['messages' => function ($query) {
                $query->orderBy('created_at', 'asc'); // Trie les messages par date
            }])
            ->findOrFail($conversationId); // Récupère la conversation ou renvoie une erreur

        $this->markMessagesAsRead($conversationId);

        return response()->json($conversation->messages); // Retourne les messages
    }


    public function sendMessage(Request $request, $conversationId)
    {
        // Utiliser l'ID de l'utilisateur authentifié
        $userId = auth()->id();
        // Récupérer la conversation en s'assurant que l'utilisateur fait partie de celle-ci
        $conversation = Conversation::whereHas('users', function ($query) use ($userId) {
            $query->where('userId', $userId); // L'utilisateur doit faire partie de la conversation
        })
            ->findOrFail($conversationId); // Si la conversation n'existe pas ou l'utilisateur n'y est pas, renvoyer une erreur

        // Valider le contenu du message
        $validated = $request->validate([
            'content' => 'required|string|max:1000', // Validation du contenu du message
        ]);

        // Créer le message et l'ajouter à la conversation
        $message = new Message([
            'senderId' => $userId,
            'content' => $validated['content'],
        ]);

        // Associer le message à la conversation
        $message->conversationId = $conversation->id;
        $message->save();

        // Mettre à jour la conversation (mettre à jour la date de dernière modification)
        $conversation->touch(); // Cela met à jour le champ `updated_at`

        // Récupérer les utilisateurs de la conversation sauf l'envoyeur
        $recipients = $conversation->users()
            ->where('user.id', '!=', $userId)
            ->pluck('user.id')
            ->toArray();

        // Diffuser l'événement
        foreach ($recipients as $recipientId) {
            Log::info('Broadcasting to user: ' . $recipientId);
            event(new MessageSent([
                'type' => 'NewMessage',
                'conversationId' => $conversation->id,
                'recipientId' => $recipientId, // Identifiant unique pour l'utilisateur concerné
            ]));
        }

        // Retourner le message créé
        return response()->json($message, 201);
    }

    public function getCurrentUser() {
        $currentUserId = Auth::id();
        return response()->json($currentUserId);
    }

    public function markMessagesAsRead($conversationId)
    {
        $userId = auth()->id(); // Identifiant de l'utilisateur connecté

        // Récupérer tous les messages non lus de la conversation pour cet utilisateur
        $messages = Message::where('conversationId', $conversationId)
            ->where('isRead', false)
            ->get();

        // Marquer chaque message comme lu par l'utilisateur
        foreach ($messages as $message) {
            $message->markAsRead($userId);
        }

        return response()->json(['message' => 'Messages marqués comme lus']);
    }

    public function getUnreadMessagesCount($conversationId)
    {
        $unreadCount = Message::where('conversationId', $conversationId)
            ->where('isRead', false)
            ->where('senderId', '!=', auth()->id()) // Ne pas compter les messages envoyés par l'utilisateur
            ->count();

        return $unreadCount;
    }

    public function getUnreadConversationsCount()
    {
        $unreadConversationsCount = Conversation::whereHas('messages', function ($query) {
            $query->where('isRead', false)
                ->where('senderId', '!=', auth()->id());
        })
            ->whereHas('users', function ($query) {
                $query->where('userId', auth()->id());
            })
            ->count();

        return $unreadConversationsCount;
    }

}
