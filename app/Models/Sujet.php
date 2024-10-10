<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sujet
 *
 * @property int $idSujet
 * @property string $titre
 * @property int $idForum
 * @property int $idUtilisateur
 * @property int $idStatutSujet
 *
 * @property Forum $forum
 * @property Utilisateur $utilisateur
 * @property StatutSujet $statutsujet
 * @property Collection|Post[] $posts
 *
 * @package App\Models
 */
class Sujet extends Model
{
	protected $table = 'sujet';
	protected $primaryKey = 'idSujet';
	public $timestamps = false;

	protected $casts = [
		'idForum' => 'int',
		'idUtilisateur' => 'int',
		'idStatutSujet' => 'int'
	];

	protected $fillable = [
		'titre',
		'idForum',
		'idUtilisateur',
		'idStatutSujet'
	];

    public static function rules(): array
    {
        return [
            'titre' => [
                'required',
                'string',
                'max:255',
            ],
            'idForum' => [
                'required',
                'integer',
                'exists:forum,idForum',
            ],
            'idUtilisateur' => [
                'required',
                'integer',
                'exists:utilisateur,idUtilisateur',
            ],
            'idStatutSujet' => [
                'required',
                'integer',
                'exists:statut_sujet,idStatutSujet',
            ],
        ];
    }

	public function forum()
	{
		return $this->belongsTo(Forum::class, 'idForum');
	}

	public function utilisateur()
	{
		return $this->belongsTo(Utilisateur::class, 'idUtilisateur');
	}

	public function statutsujet()
	{
		return $this->belongsTo(StatutSujet::class, 'idStatutSujet');
	}

	public function posts()
	{
		return $this->hasMany(Post::class, 'idSujet');
	}
}
