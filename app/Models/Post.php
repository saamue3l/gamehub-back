<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Post
 *
 * @property int $id
 * @property string $content
 * @property Carbon|null $creationDate
 * @property int $userId
 * @property int $topicId
 * @property int $postStatusId
 *
 * @property User $user
 * @property Topic $topic
 * @property PostStatus $poststatus
 * @property Collection|Reaction[] $reactions
 *
 * @package App\Models
 */
class Post extends Model
{
	protected $table = 'post';
	public $timestamps = false;

	protected $casts = [
		'creationDate' => 'datetime',
		'userId' => 'int',
		'topicId' => 'int',
		'postStatusId' => 'int'
	];

	protected $fillable = [
		'content',
		'creationDate',
		'userId',
		'topicId',
		'postStatusId'
	];

    public static function rules(): array
    {
        return [
            'content' => 'required|string',
            'creationDate' => 'nullable|date',
            'userId' => 'required|integer|exists:user,id',
            'topicId' => 'required|integer|exists:topic,id',
            'postStatusId' => 'required|integer|exists:poststatus,id',
        ];
    }


    public function user()
	{
		return $this->belongsTo(User::class, 'userId');
	}

	public function topic()
	{
		return $this->belongsTo(Topic::class, 'topicId');
	}

	public function poststatus()
	{
		return $this->belongsTo(PostStatus::class, 'postStatusId');
	}

	public function reactions()
	{
		return $this->hasMany(Reaction::class, 'postId');
	}
}
