<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Action
 *
 * @property int $actionId
 * @property int $xpEarned
 * @property string $actionType
 *
 * @property Collection|ActionHistory[] $actionhistories
 *
 * @package App\Models
 */
class Action extends Model
{
	protected $table = 'action';
	public $timestamps = false;

	protected $casts = [
		'xpEarned' => 'int'
	];

	protected $fillable = [
		'xpEarned',
		'actionType'
	];

    public static function rules(): array
    {
        return [
            'xpEarned' => 'required|integer|min:0',
            'actionType' => 'required|string|max:100',
        ];
    }


    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'actionhistory', 'actionId', 'userId')->withPivot('actionDate');
    }
}
