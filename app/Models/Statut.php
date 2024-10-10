<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Statut
 *
 * @property int $idStatut
 * @property string $libelle
 *
 * @property Collection|Utilisateur[] $utilisateurs
 *
 * @package App\Models
 */
class Statut extends Model
{
	protected $table = 'statut';
	protected $primaryKey = 'idStatut';
	public $timestamps = false;

	protected $fillable = [
		'libelle'
	];

    public static function rules(): array
    {
        return [
            'libelle' => 'required|string|max:255'
        ];
    }

	public function utilisateurs()
	{
		return $this->hasMany(Utilisateur::class, 'idStatut');
	}
}
