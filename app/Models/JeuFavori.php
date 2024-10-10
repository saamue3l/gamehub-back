<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class JeuFavori
 *
 * @property int $idJeuFavori
 * @property string|null $description
 * @property int $idPlateforme
 * @property int $idTypeCompetence
 * @property int $idJeu
 * @property int $idUtilisateur
 *
 * @property Plateforme $plateforme
 * @property TypeCompetence $typecompetence
 * @property Jeu $jeu
 * @property Utilisateur $utilisateur
 *
 * @package App\Models
 */
class JeuFavori extends Model
{
	protected $table = 'jeufavori';
	protected $primaryKey = 'idJeuFavori';
	public $timestamps = false;

	protected $casts = [
		'idPlateforme' => 'int',
		'idTypeCompetence' => 'int',
		'idJeu' => 'int',
		'idUtilisateur' => 'int'
	];

	protected $fillable = [
		'description',
		'idPlateforme',
		'idTypeCompetence',
		'idJeu',
		'idUtilisateur'
	];

    public static function rules()
    {
        return [
            'idPlateforme' => 'required|integer',
            'idTypeCompetence' => 'required|integer',
            'idJeu' => 'required|integer',
            'idUtilisateur' => 'required|integer',
            'description' => 'nullable|string|max:1000'
        ];
    }

	public function plateforme()
	{
		return $this->belongsTo(Plateforme::class, 'idPlateforme');
	}

	public function typecompetence()
	{
		return $this->belongsTo(TypeCompetence::class, 'idTypeCompetence');
	}

	public function jeu()
	{
		return $this->belongsTo(Jeu::class, 'idJeu');
	}

	public function utilisateur()
	{
		return $this->belongsTo(Utilisateur::class, 'idUtilisateur');
	}
}
