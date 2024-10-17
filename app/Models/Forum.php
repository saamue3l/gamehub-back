<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Forum
 *
 * @property int $id
 * @property string $name
 *
 * @property Collection|Topic[] $topics
 *
 * @package App\Models
 */
class Forum extends Model
{
	protected $table = 'forum';
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

    public function topics()
	{
		return $this->hasMany(Topic::class, 'forumId');
	}
}
