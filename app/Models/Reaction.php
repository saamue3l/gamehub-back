<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Reaction
 *
 * @property int $idReaction
 * @property int $idTypeReaction
 * @property int $idUtilisateur
 * @property int $idPost
 *
 * @property TypeReaction $typereaction
 * @property Utilisateur $utilisateur
 * @property Post $post
 *
 * @package App\Models
 */
class Reaction extends Model
{
	protected $table = 'reaction';
	protected $primaryKey = 'idReaction';
	public $timestamps = false;

	protected $casts = [
		'idTypeReaction' => 'int',
		'idUtilisateur' => 'int',
		'idPost' => 'int'
	];

	protected $fillable = [
		'idTypeReaction',
		'idUtilisateur',
		'idPost'
	];

    public static function rules()
    {
        return [
            'idTypeReaction' => 'required|integer',
            'idUtilisateur' => 'required|integer',
            'idPost' => 'required|integer'
        ];
    }

	public function typereaction()
	{
		return $this->belongsTo(TypeReaction::class, 'idTypeReaction');
	}

	public function utilisateur()
	{
		return $this->belongsTo(Utilisateur::class, 'idUtilisateur');
	}

	public function post()
	{
		return $this->belongsTo(Post::class, 'idPost');
	}
}
