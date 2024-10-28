<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * Class Game
 *
 * @property int $id
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
    use Searchable;

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

    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
        ];
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
