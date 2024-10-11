<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FavoriteGame
 *
 * @property int $favoriteGameId
 * @property string|null $description
 * @property int $platformId
 * @property int $skillTypeId
 * @property int $gameId
 * @property int $userId
 *
 * @property Platform $platform
 * @property SkillType $skilltype
 * @property Game $game
 * @property User $user
 *
 * @package App\Models
 */
class FavoriteGame extends Model
{
	protected $table = 'favoritegame';
	protected $primaryKey = 'favoriteGameId';
	public $timestamps = false;

	protected $casts = [
		'platformId' => 'int',
		'skillTypeId' => 'int',
		'gameId' => 'int',
		'userId' => 'int'
	];

	protected $fillable = [
		'description',
		'platformId',
		'skillTypeId',
		'gameId',
		'userId'
	];

    public static function rules(): array
    {
        return [
            'description' => 'nullable|string',
            'platformId' => 'required|integer|exists:platform,platformId',
            'skillTypeId' => 'required|integer|exists:skilltype,skillTypeId',
            'gameId' => 'required|integer|exists:game,gameId',
            'userId' => 'required|integer|exists:user,userId',
        ];
    }


    public function platform()
	{
		return $this->belongsTo(Platform::class, 'platformId');
	}

	public function skilltype()
	{
		return $this->belongsTo(SkillType::class, 'skillTypeId');
	}

	public function game()
	{
		return $this->belongsTo(Game::class, 'gameId');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'userId');
	}
}
