<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Participation
 *
 * @property int $participationId
 * @property int $eventId
 * @property int $userId
 *
 * @property Event $event
 * @property User $user
 *
 * @package App\Models
 */
class Participation extends Model
{
	protected $table = 'participation';
	protected $primaryKey = 'participationId';
	public $timestamps = false;

	protected $casts = [
		'eventId' => 'int',
		'userId' => 'int'
	];

	protected $fillable = [
		'eventId',
		'userId'
	];

    public static function rules(): array
    {
        return [
            'eventId' => 'required|integer|exists:event,eventId',
            'userId' => 'required|integer|exists:user,userId',
        ];
    }


    public function event()
	{
		return $this->belongsTo(Event::class, 'eventId');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'userId');
	}
}
