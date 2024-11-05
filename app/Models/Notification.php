<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'typeId',
        'message',
        'readAt',
        'processedAt',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function type()
    {
        return $this->belongsTo(NotificationType::class, 'typeId');
    }
}
