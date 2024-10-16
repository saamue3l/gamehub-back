<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

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
    use HasApiTokens, HasFactory;

	protected $table = 'user';
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

	public function successes()
	{
        return $this->belongsToMany(Success::class, "achievedsuccess", "userId", "successId")->withPivot("achievementDate");
	}

	public function actions()
	{
		return $this->belongsToMany(Action::class, 'actionhistory', 'userId', 'actionId')->withPivot('actionDate');
	}

	public function availabilities()
	{
		return $this->hasMany(Availability::class, 'userId');
	}

	public function createdEvents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
		return $this->hasMany(Event::class, 'userId');
	}

	public function favoritegames()
	{
		return $this->hasMany(FavoriteGame::class, 'userId');
	}

	public function participations()
	{
        return $this->belongsToMany(Event::class, 'participation', 'userId', 'eventId');
    }

	public function posts()
	{
		return $this->hasMany(Post::class, 'userId');
	}

	public function reactions()
	{
		return $this->hasMany(Reaction::class, 'userId');
	}

	public function createdTopics()
	{
		return $this->hasMany(Topic::class, 'userId');
	}

	public function gamesUsernames()
	{
		return $this->hasMany(Username::class, 'userId');
	}
}
