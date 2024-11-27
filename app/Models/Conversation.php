<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // Nom facultatif pour une conversation
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'conversation_user', 'conversationId', 'userId')
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'conversationId'); // Spécifiez la clé étrangère correcte
    }
}
