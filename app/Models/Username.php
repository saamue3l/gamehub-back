<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Username
 *
 * @property int $usernameId
 * @property string $username
 * @property int $platformId
 * @property int $userId
 *
 * @property Platform $platform
 * @property User $user
 *
 * @package App\Models
 */
class Username extends Model
{
	protected $table = 'username';
	protected $primaryKey = 'usernameId';
	public $timestamps = false;

	protected $casts = [
		'platformId' => 'int',
		'userId' => 'int'
	];

	protected $fillable = [
		'username',
		'platformId',
		'userId'
	];

    public static function rules(): array
    {
        return [
            'username' => 'required|string|max:50',
            'platformId' => 'required|integer|exists:platform,platformId',
            'userId' => 'required|integer|exists:user,userId',
        ];
    }


    public function platform()
	{
		return $this->belongsTo(Platform::class, 'platformId');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'userId');
	}
}
