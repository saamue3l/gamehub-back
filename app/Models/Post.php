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
 * @property int $idPost
 * @property string $contenu
 * @property Carbon|null $dateCreation
 * @property int $idUtilisateur
 * @property int $idSujet
 * @property int $idStatutPost
 *
 * @property Utilisateur $utilisateur
 * @property Sujet $sujet
 * @property StatutPost $statutpost
 * @property Collection|Reaction[] $reactions
 *
 * @package App\Models
 */
class Post extends Model
{
	protected $table = 'post';
	protected $primaryKey = 'idPost';
	public $timestamps = false;

	protected $casts = [
		'dateCreation' => 'datetime',
		'idUtilisateur' => 'int',
		'idSujet' => 'int',
		'idStatutPost' => 'int'
	];

	protected $fillable = [
		'contenu',
		'dateCreation',
		'idUtilisateur',
		'idSujet',
		'idStatutPost'
	];

    public static function rules()
    {
        return [
            'contenu' => 'required|string|max:1000',
            'idUtilisateur' => 'required|integer',
            'idSujet' => 'required|integer',
            'idStatutPost' => 'required|integer',
        ];
    }

	public function utilisateur()
	{
		return $this->belongsTo(Utilisateur::class, 'idUtilisateur');
	}

	public function sujet()
	{
		return $this->belongsTo(Sujet::class, 'idSujet');
	}

	public function statutpost()
	{
		return $this->belongsTo(StatutPost::class, 'idStatutPost');
	}

	public function reactions()
	{
		return $this->hasMany(Reaction::class, 'idPost');
	}
}
