<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TypeReaction
 *
 * @property int $idTypeReaction
 * @property string $emoji
 *
 * @property Collection|Reaction[] $reactions
 *
 * @package App\Models
 */
class TypeReaction extends Model
{
	protected $table = 'typereaction';
	protected $primaryKey = 'idTypeReaction';
	public $timestamps = false;

	protected $fillable = [
		'emoji'
	];

    public function rules(): array
    {
        return [
            'emoji' => 'required|string|max:10',
        ];
    }

	public function reactions()
	{
		return $this->hasMany(Reaction::class, 'idTypeReaction');
	}
}
