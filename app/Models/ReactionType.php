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
 * @property int $reactionTypeId
 * @property string $emoji
 *
 * @property Collection|Reaction[] $reactions
 *
 * @package App\Models
 */
class ReactionType extends Model
{
	protected $table = 'reactiontype';
	protected $primaryKey = 'reactionTypeId';
	public $timestamps = false;

	protected $fillable = [
		'emoji'
	];

    public static function rules(): array
    {
        return [
            'emoji' => 'required|string|max:10',
        ];
    }

    public function reactions()
	{
		return $this->hasMany(Reaction::class, 'reactionTypeId');
	}
}
