<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Reaction
 *
 * @property int $reactionId
 * @property int $reactionTypeId
 * @property int $userId
 * @property int $postId
 *
 * @property ReactionType $reactiontype
 * @property User $user
 * @property Post $post
 *
 * @package App\Models
 */
class Reaction extends Model
{
	protected $table = 'reaction';
	public $timestamps = false;

	protected $casts = [
		'reactionTypeId' => 'int',
		'userId' => 'int',
		'postId' => 'int'
	];

	protected $fillable = [
		'reactionTypeId',
		'userId',
		'postId'
	];

    public static function rules(): array
    {
        return [
            'reactionTypeId' => 'required|integer|exists:reactiontype,reactionTypeId',
            'userId' => 'required|integer|exists:user,userId',
            'postId' => 'required|integer|exists:post,postId',
        ];
    }


    public function reactiontype()
	{
		return $this->belongsTo(ReactionType::class, 'reactionTypeId');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'userId');
	}

	public function post()
	{
		return $this->belongsTo(Post::class, 'postId');
	}
}
