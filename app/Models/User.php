<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 *
 * @property int $userId
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string|null $picture
 * @property int|null $xp
 * @property int $statusId
 * @property int $roleId
 *
 * @property Status $status
 * @property Role $role
 * @property Collection|AchievedSuccess[] $achievedsuccesses
 * @property Collection|ActionHistory[] $actionhistories
 * @property Collection|Availability[] $availabilities
 * @property Collection|Event[] $events
 * @property Collection|FavoriteGame[] $favoritegames
 * @property Collection|Participation[] $participations
 * @property Collection|Post[] $posts
 * @property Collection|Reaction[] $reactions
 * @property Collection|Topic[] $topics
 * @property Collection|Username[] $usernames
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	protected $table = 'user';
	protected $primaryKey = 'userId';
	public $timestamps = false;

	protected $casts = [
		'xp' => 'int',
		'statusId' => 'int',
		'roleId' => 'int'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'username',
		'email',
		'password',
		'picture',
		'xp',
		'statusId',
		'roleId'
	];

    public static function rules(): array
    {
        return [
            'username' => 'required|string|max:50|unique:user,username',
            'email' => 'required|string|email|max:100|unique:user,email',
            'password' => 'required|string|min:8',
            'photo' => 'nullable|string',
            'xp' => 'nullable|integer|min:0',
            'statusId' => 'required|integer|exists:status,statusId',
            'roleId' => 'required|integer|exists:role,roleId',
        ];
    }

    public function status()
	{
		return $this->belongsTo(Status::class, 'statusId');
	}

	public function role()
	{
		return $this->belongsTo(Role::class, 'roleId');
	}

	public function achievedsuccesses()
	{
		return $this->hasMany(AchievedSuccess::class, 'userId');
	}

	public function actionhistories()
	{
		return $this->hasMany(ActionHistory::class, 'userId');
	}

	public function availabilities()
	{
		return $this->hasMany(Availability::class, 'userId');
	}

	public function events()
	{
		return $this->hasMany(Event::class, 'userId');
	}

	public function favoritegames()
	{
		return $this->hasMany(FavoriteGame::class, 'userId');
	}

	public function participations()
	{
		return $this->hasMany(Participation::class, 'userId');
	}

	public function posts()
	{
		return $this->hasMany(Post::class, 'userId');
	}

	public function reactions()
	{
		return $this->hasMany(Reaction::class, 'userId');
	}

	public function topics()
	{
		return $this->hasMany(Topic::class, 'userId');
	}

	public function usernames()
	{
		return $this->hasMany(Username::class, 'userId');
	}
}
