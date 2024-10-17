<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Availability
 *
 * @property int $id
 * @property string $dayOfWeek
 * @property bool|null $morning
 * @property bool|null $afternoon
 * @property bool|null $evening
 * @property bool|null $night
 * @property int $userId
 *
 * @property User $user
 *
 * @package App\Models
 */
class Availability extends Model
{
	protected $table = 'availability';
	public $timestamps = false;

	protected $casts = [
		'morning' => 'bool',
		'afternoon' => 'bool',
		'evening' => 'bool',
		'night' => 'bool',
		'userId' => 'int'
	];

	protected $fillable = [
		'dayOfWeek',
		'morning',
		'afternoon',
		'evening',
		'night',
		'userId'
	];

    public static function rules(): array
    {
        return [
            'dayOfWeek' => 'required|string|max:20',
            'morning' => 'nullable|boolean',
            'afternoon' => 'nullable|boolean',
            'evening' => 'nullable|boolean',
            'night' => 'nullable|boolean',
            'userId' => 'required|integer|exists:user,id',
        ];
    }


    public function user()
	{
		return $this->belongsTo(User::class, 'userId');
	}
}
