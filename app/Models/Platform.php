<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Platform
 *
 * @property int $platformId
 * @property string $name
 *
 * @property Collection|FavoriteGame[] $favoritegames
 * @property Collection|Username[] $usernames
 *
 * @package App\Models
 */
class Platform extends Model
{
	protected $table = 'platform';
	protected $primaryKey = 'platformId';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

    public static function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
        ];
    }

    public function favoritegames()
	{
		return $this->hasMany(FavoriteGame::class, 'platformId');
	}

	public function usernames()
	{
		return $this->hasMany(Username::class, 'platformId');
	}
}
