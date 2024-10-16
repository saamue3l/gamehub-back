<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    use HasFactory;

	protected $table = 'game';
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

    /**
     * Platforms supported by this game
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function platforms(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Platform::class, 'game_platform', 'gameId', 'platformId');
    }

    public function events(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(Event::class, 'gameId');
	}

	public function favorites(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
		return $this->hasMany(FavoriteGame::class, 'gameId');
	}
}
