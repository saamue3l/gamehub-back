<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Participation
 *
 * @property int $idParticipation
 * @property int $idEvenement
 * @property int $idUtilisateur
 *
 * @property Evenement $evenement
 * @property Utilisateur $utilisateur
 *
 * @package App\Models
 */
class Participation extends Model
{
	protected $table = 'participation';
	protected $primaryKey = 'idParticipation';
	public $timestamps = false;

	protected $casts = [
		'idEvenement' => 'int',
		'idUtilisateur' => 'int'
	];

	protected $fillable = [
		'idEvenement',
		'idUtilisateur'
	];

    public static function rules()
    {
        return [
            'idEvenement' => 'required|integer',
            'idUtilisateur' => 'required|integer',
        ];
    }

	public function evenement()
	{
		return $this->belongsTo(Evenement::class, 'idEvenement');
	}

	public function utilisateur()
	{
		return $this->belongsTo(Utilisateur::class, 'idUtilisateur');
	}
}
