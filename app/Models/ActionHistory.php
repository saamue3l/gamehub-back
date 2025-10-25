<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActionHistory extends Model
{
    protected $table = 'actionhistory';
    protected $primaryKey = ['userId', 'actionId'];
    public $incrementing = false;
    protected $keyType = 'array';
    public $timestamps = false;

    protected $fillable = ['userId', 'actionId', 'actionDate'];
    protected $dates = ['actionDate'];

    protected $casts = [
        'actionDate' => 'datetime'
    ];

    public function getKeyName()
    {
        return $this->primaryKey;
    }

    protected function setKeysForSaveQuery($query)
    {
        foreach ($this->getKeyName() as $key) {
            $query->where($key, '=', $this->getAttribute($key));
        }

        return $query;
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class, 'actionId');
    }
}
