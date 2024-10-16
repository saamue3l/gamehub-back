<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Topic
 *
 * @property int $topicId
 * @property string $title
 * @property int $forumId
 * @property int $userId
 * @property int $topicStatusId
 *
 * @property Forum $forum
 * @property User $user
 * @property TopicStatus $topicstatus
 * @property Collection|Post[] $posts
 *
 * @package App\Models
 */
class Topic extends Model
{
	protected $table = 'topic';
	protected $primaryKey = 'topicId';
	public $timestamps = false;

	protected $casts = [
		'forumId' => 'int',
		'userId' => 'int',
		'topicStatusId' => 'int'
	];

	protected $fillable = [
		'title',
		'forumId',
		'userId',
		'topicStatusId'
	];

    public static function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'forumId' => 'required|integer|exists:forum,forumId',
            'userId' => 'required|integer|exists:user,userId',
            'topicStatusId' => 'required|integer|exists:topicstatus,topicStatusId',
        ];
    }


    public function forum()
	{
		return $this->belongsTo(Forum::class, 'forumId');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'userId');
	}

	public function topicstatus()
	{
		return $this->belongsTo(TopicStatus::class, 'topicStatusId');
	}

	public function posts()
	{
		return $this->hasMany(Post::class, 'topicId');
	}
}
