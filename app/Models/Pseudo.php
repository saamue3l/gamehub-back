<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Pseudo
 *
 * @property int $idPseudo
 * @property string $pseudo
 * @property int $idPlateforme
 * @property int $idUtilisateur
 *
 * @property Plateforme $plateforme
 * @property Utilisateur $utilisateur
 *
 * @package App\Models
 */
class Pseudo extends Model
{
	protected $table = 'pseudo';
	protected $primaryKey = 'idPseudo';
	public $timestamps = false;

	protected $casts = [
		'idPlateforme' => 'int',
		'idUtilisateur' => 'int'
	];

	protected $fillable = [
		'pseudo',
		'idPlateforme',
		'idUtilisateur'
	];

    public static function rules()
    {
        return [
            'pseudo' => 'required|string|max:50',
            'idPlateforme' => 'required|integer',
            'idUtilisateur' => 'required|integer'
        ];
    }

	public function plateforme()
	{
		return $this->belongsTo(Plateforme::class, 'idPlateforme');
	}

	public function utilisateur()
	{
		return $this->belongsTo(Utilisateur::class, 'idUtilisateur');
	}
}
