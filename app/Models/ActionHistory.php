<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ActionHistory
 *
 * @property int $userId
 * @property int $actionId
 * @property Carbon $actionDate
 *
 * @property User $user
 * @property Action $action
 *
 * @package App\Models
 */
class ActionHistory extends Model
{
	protected $table = 'actionhistory';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'userId' => 'int',
		'actionId' => 'int',
		'actionDate' => 'datetime'
	];

    public static function rules(): array
    {
        return [
            'actionDate' => 'required|date',
            'userId' => 'required|integer|exists:user,userId',
            'actionId' => 'required|integer|exists:action,actionId',
        ];
    }


    protected $fillable = [
		'actionDate'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'userId');
	}

	public function action()
	{
		return $this->belongsTo(Action::class, 'actionId');
	}
}
