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
 * @property int $id
 * @property string|null $description
 * @property int $maxPlayers
 * @property Carbon $eventDate
 * @property int $creatorId
 * @property int $gameId
 *
 * @property User $creatorId
 * @property Game $game
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
		'creatorId' => 'int',
		'gameId' => 'int'
	];

	protected $fillable = [
		'description',
		'maxPlayers',
		'eventDate',
		'creatorId',
		'gameId'
	];

    public static function rules(): array
    {
        return [
            'description' => 'nullable|string',
            'maxPlayers' => 'required|integer|min:1',
            'eventDate' => 'required|date',
            'creatorId' => 'required|integer|exists:user,id',
            'gameId' => 'required|integer|exists:game,id',
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
