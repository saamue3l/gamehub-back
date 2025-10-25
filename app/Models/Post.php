<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Post extends Model
{
    use Searchable;
    use hasFactory;

	protected $table = 'post';
	public $timestamps = false;

	protected $casts = [
		'creationDate' => 'datetime',
		'userId' => 'int',
		'topicId' => 'int'
	];

	protected $fillable = [
		'content',
		'creationDate',
		'userId',
		'topicId'
	];

    public static function rules(): array
    {
        return [
            'content' => 'required|string',
            'creationDate' => 'nullable|date',
            'userId' => 'required|integer|exists:user,id',
            'topicId' => 'required|integer|exists:topic,id'
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

	public function reactions()
	{
		return $this->hasMany(Reaction::class, 'postId');
	}

    public function getGroupedReactions()
    {
        return $this->reactions
            ->groupBy('reactiontype.emoji')
            ->map(function ($reactions, $emoji) {
                return [
                    'emoji' => $emoji,
                    'users' => $reactions->pluck('user')->unique('id')->values()
                ];
            })
            ->sortBy(function ($group) {
                        return $this->reactions
                    ->firstWhere('reactiontype.emoji', $group['emoji'])
                    ->reactiontype->id ?? 0;
            })
            ->values();
    }

    public function reactToPost(User $user, ReactionType $reactionType) {
        return $this->reactions()->create(
            [
                "reactionTypeId" => $reactionType->id,
                "userId" => $user->id,
                "postId" => $this->id
            ]
        );
    }
}
