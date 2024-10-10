<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Evenement
 *
 * @property int $idEvenement
 * @property string|null $description
 * @property int $nbJoueursMax
 * @property Carbon $dateEvenement
 * @property int $idUtilisateur
 * @property int $idJeu
 *
 * @property Utilisateur $utilisateur
 * @property Jeu $jeu
 * @property Collection|Participation[] $participations
 *
 * @package App\Models
 */
class Evenement extends Model
{
	protected $table = 'evenement';
	protected $primaryKey = 'idEvenement';
	public $timestamps = false;

	protected $casts = [
		'nbJoueursMax' => 'int',
		'dateEvenement' => 'datetime',
		'idUtilisateur' => 'int',
		'idJeu' => 'int'
	];

	protected $fillable = [
		'description',
		'nbJoueursMax',
		'dateEvenement',
		'idUtilisateur',
		'idJeu'
	];

    public static function rules()
    {
        return [
            'description' => 'nullable|string|max:1000',
            'nbJoueursMax' => 'required|integer|min:1',
            'dateEvenement' => 'required|date',
            'idUtilisateur' => 'required|integer',
            'idJeu' => 'required|integer',
        ];
    }

	public function utilisateur()
	{
		return $this->belongsTo(Utilisateur::class, 'idUtilisateur');
	}

	public function jeu()
	{
		return $this->belongsTo(Jeu::class, 'idJeu');
	}

	public function participations()
	{
		return $this->hasMany(Participation::class, 'idEvenement');
	}
}
