<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AchievedSuccess
 *
 * @property int $userId
 * @property int $successId
 * @property Carbon $achievementDate
 *
 * @property User $user
 * @property Success $success
 *
 * @package App\Models
 */
class AchievedSuccess extends Model
{
	protected $table = 'achievedsuccess';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'userId' => 'int',
		'successId' => 'int',
		'achievementDate' => 'datetime'
	];

	protected $fillable = [
		'achievementDate'
	];

    public static function rules(): array
    {
        return [
            'achievementDate' => 'required|date',
            'userId' => 'required|integer|exists:user,userId',
            'successId' => 'required|integer|exists:success,successId',
        ];
    }


    public function user()
	{
		return $this->belongsTo(User::class, 'userId');
	}

	public function success()
	{
		return $this->belongsTo(Success::class, 'successId');
	}
}
