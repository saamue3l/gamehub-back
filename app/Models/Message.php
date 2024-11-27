<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversationId',
        'senderId',
        'content',
    ];

    // Définition de la relation avec Conversation
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversationId');
    }

    // Définition de la relation avec User (pour l'expéditeur)
    public function sender()
    {
        return $this->belongsTo(User::class, 'senderId');
    }
}
