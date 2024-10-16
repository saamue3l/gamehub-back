<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PostStatus
 *
 * @property int $postStatusId
 * @property string $label
 *
 * @property Collection|Post[] $posts
 *
 * @package App\Models
 */
class PostStatus extends Model
{
	protected $table = 'poststatus';
	public $timestamps = false;

	protected $fillable = [
		'label'
	];

    public static function rules(): array
    {
        return [
            'label' => 'required|string|max:100',
        ];
    }


    public function posts()
	{
		return $this->hasMany(Post::class, 'postStatusId');
	}
}
