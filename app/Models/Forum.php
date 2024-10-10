<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Forum
 *
 * @property int $idForum
 * @property string $nom
 *
 * @property Collection|Sujet[] $sujets
 *
 * @package App\Models
 */
class Forum extends Model
{
	protected $table = 'forum';
	protected $primaryKey = 'idForum';
	public $timestamps = false;

	protected $fillable = [
		'nom'
	];

    public static function rules()
    {
        return [
            'nom' => 'required|string|max:255',
        ];
    }

	public function sujets()
	{
		return $this->hasMany(Sujet::class, 'idForum');
	}
}
