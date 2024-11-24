<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReactionType
 *
 * @property int $id
 * @property string $emoji
 *
 * @property Collection|Reaction[] $reactions
 *
 * @package App\Models
 */
class ReactionType extends Model
{
	protected $table = 'reactiontype';
	public $timestamps = false;

	protected $fillable = [
		'emoji'
	];

    public static function rules(): array
    {
        return [
            'emoji' => 'unique|required|string|max:10',
        ];
    }

    public function reactions()
	{
		return $this->hasMany(Reaction::class, 'reactionTypeId');
	}
}
