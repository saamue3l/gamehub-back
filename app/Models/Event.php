<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Event
 *
 * @property int $eventId
 * @property string|null $description
 * @property int $maxPlayers
 * @property Carbon $eventDate
 * @property int $userId
 * @property int $gameId
 *
 * @property User $user
 * @property Game $game
 * @property Collection|Participation[] $participations
 *
 * @package App\Models
 */
class Event extends Model
{
	protected $table = 'event';
	public $timestamps = false;

	protected $casts = [
		'maxPlayers' => 'int',
		'eventDate' => 'datetime',
		'userId' => 'int',
		'gameId' => 'int'
	];

	protected $fillable = [
		'description',
		'maxPlayers',
		'eventDate',
		'userId',
		'gameId'
	];

    public static function rules(): array
    {
        return [
            'description' => 'nullable|string',
            'maxPlayers' => 'required|integer|min:1',
            'eventDate' => 'required|date',
            'userId' => 'required|integer|exists:user,userId',
            'gameId' => 'required|integer|exists:game,gameId',
        ];
    }


    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
		return $this->belongsTo(User::class, 'creatorId');
	}

    public function participants(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'participation', 'eventId', 'userId');
    }

	public function game(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
		return $this->belongsTo(Game::class, 'gameId');
	}
}
