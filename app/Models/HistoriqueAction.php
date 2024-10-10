<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HistoriqueAction
 *
 * @property int $idUtilisateur
 * @property int $idAction
 * @property Carbon $dateAction
 *
 * @property Utilisateur $utilisateur
 * @property Action $action
 *
 * @package App\Models
 */
class HistoriqueAction extends Model
{
	protected $table = 'historiqueaction';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'idUtilisateur' => 'int',
		'idAction' => 'int',
		'dateAction' => 'datetime'
	];

	protected $fillable = [
		'dateAction'
	];

    public static function rules()
    {
        return [
            'idUtilisateur' => 'required|integer',
            'idAction' => 'required|integer',
            'dateAction' => 'required|date',
        ];
    }

	public function utilisateur()
	{
		return $this->belongsTo(Utilisateur::class, 'idUtilisateur');
	}

	public function action()
	{
		return $this->belongsTo(Action::class, 'idAction');
	}
}
