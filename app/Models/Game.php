<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Game
 *
 * @property int $gameId
 * @property string $name
 * @property string|null $cover
 *
 * @property Collection|Event[] $events
 * @property Collection|FavoriteGame[] $favoritegames
 *
 * @package App\Models
 */
class Game extends Model
{
	protected $table = 'game';
	protected $primaryKey = 'gameId';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'cover'
	];

    public static function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'cover' => 'nullable|string',
        ];
    }


    public function events()
	{
		return $this->hasMany(Event::class, 'gameId');
	}

	public function favoritegames()
	{
		return $this->hasMany(FavoriteGame::class, 'gameId');
	}
}
