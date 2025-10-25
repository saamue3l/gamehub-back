<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Reaction extends Model
{
    use hasFactory;

	protected $table = 'reaction';
	public $timestamps = false;
    protected $hidden = ['reactionTypeId', 'userId', 'postId'];

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
            'reactionTypeId' => 'required|integer|exists:reactiontype,id',
            'userId' => 'required|integer|exists:user,id',
            'postId' => 'required|integer|exists:post,id',
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
