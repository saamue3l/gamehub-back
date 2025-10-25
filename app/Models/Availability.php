<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Availability extends Model
{
    use HasFactory;

    protected $table = 'availability';
    public $timestamps = false;

    protected $casts = [
        'morning' => 'bool',
        'afternoon' => 'bool',
        'evening' => 'bool',
        'night' => 'bool',
        'userId' => 'int'
    ];

    protected $fillable = [
        'dayOfWeek',
        'morning',
        'afternoon',
        'evening',
        'night',
        'userId'
    ];

    public static function rules(): array
    {
        return [
            'dayOfWeek' => 'required|string|in:Lun,Mar,Mer,Jeu,Ven,Sam,Dim',
            'morning' => 'nullable|boolean',
            'afternoon' => 'nullable|boolean',
            'evening' => 'nullable|boolean',
            'night' => 'nullable|boolean',
            'userId' => 'required|integer|exists:user,id',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
