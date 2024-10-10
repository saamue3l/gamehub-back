<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SuccesObtenu
 *
 * @property int $idUtilisateur
 * @property int $idSucces
 * @property Carbon $dateObtention
 *
 * @property Utilisateur $utilisateur
 * @property Succes $succe
 *
 * @package App\Models
 */
class SuccesObtenu extends Model
{
	protected $table = 'succesobtenu';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'idUtilisateur' => 'int',
		'idSucces' => 'int',
		'dateObtention' => 'datetime'
	];

	protected $fillable = [
		'dateObtention'
	];

    public static function rules(): array
    {
        return [
            'dateObtention' => 'required|date',
            'idUtilisateur' => 'required|integer|exists:utilisateur,idUtilisateur',
            'idSucces' => 'required|integer|exists:succe,idSucces'
        ];
    }

	public function utilisateur()
	{
		return $this->belongsTo(Utilisateur::class, 'idUtilisateur');
	}

	public function succe()
	{
		return $this->belongsTo(Succes::class, 'idSucces');
	}
}
