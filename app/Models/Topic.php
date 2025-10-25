<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Topic extends Model
{
    use Searchable;
    use hasFactory;

	protected $table = 'topic';
	public $timestamps = false;

	protected $casts = [
		'forumId' => 'int',
		'creatorId' => 'int'
	];

	protected $fillable = [
		'title',
		'forumId',
		'creatorId'
	];

    public static function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'forumId' => 'required|integer|exists:forum,id',
            'creatorId' => 'required|integer|exists:user,id'
        ];
    }


    public function forum()
	{
		return $this->belongsTo(Forum::class, 'forumId');
	}

	public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
		return $this->belongsTo(User::class, 'creatorId');
	}

	public function posts()
	{
		return $this->hasMany(Post::class, 'topicId');
	}
}
