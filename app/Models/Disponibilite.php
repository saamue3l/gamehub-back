<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Disponibilite
 *
 * @property int $idDisponibilite
 * @property string $jourSemaine
 * @property bool|null $matin
 * @property bool|null $apresMidi
 * @property bool|null $soir
 * @property bool|null $nuit
 * @property int $idUtilisateur
 *
 * @property Utilisateur $utilisateur
 *
 * @package App\Models
 */
class Disponibilite extends Model
{
	protected $table = 'disponibilite';
	protected $primaryKey = 'idDisponibilite';
	public $timestamps = false;

	protected $casts = [
		'matin' => 'bool',
		'apresMidi' => 'bool',
		'soir' => 'bool',
		'nuit' => 'bool',
		'idUtilisateur' => 'int'
	];

	protected $fillable = [
		'jourSemaine',
		'matin',
		'apresMidi',
		'soir',
		'nuit',
		'idUtilisateur'
	];

    public static function rules()
    {
        return [
            'jourSemaine' => 'required|string|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'matin' => 'nullable|boolean',
            'apresMidi' => 'nullable|boolean',
            'soir' => 'nullable|boolean',
            'nuit' => 'nullable|boolean',
            'idUtilisateur' => 'required|integer',
        ];
    }

	public function utilisateur()
	{
		return $this->belongsTo(Utilisateur::class, 'idUtilisateur');
	}
}
