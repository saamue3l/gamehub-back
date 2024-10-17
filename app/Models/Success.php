<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Success
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 *
 * @package App\Models
 */
class Success extends Model
{
	protected $table = 'success';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'description'
	];

    public static function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ];
    }

    public function usersWithSuccess()
	{
		return $this->belongsToMany(User::class, "achievedsuccess", "successId", "userId")->withPivot("achievementDate");
	}
}
