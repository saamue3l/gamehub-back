<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Plateforme
 *
 * @property int $idPlateforme
 * @property string $nom
 *
 * @property Collection|JeuFavori[] $jeufavoris
 * @property Collection|Pseudo[] $pseudos
 *
 * @package App\Models
 */
class Plateforme extends Model
{
	protected $table = 'plateforme';
	protected $primaryKey = 'idPlateforme';
	public $timestamps = false;

	protected $fillable = [
		'nom'
	];

    public static function rules()
    {
        return [
            'nom' => 'required|string|max:255',
        ];
    }

	public function jeufavoris()
	{
		return $this->hasMany(JeuFavori::class, 'idPlateforme');
	}

	public function pseudos()
	{
		return $this->hasMany(Pseudo::class, 'idPlateforme');
	}
}
