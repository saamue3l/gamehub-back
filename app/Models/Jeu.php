<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Jeu
 *
 * @property int $idJeu
 * @property string $nom
 * @property string|null $jaquette
 *
 * @property Collection|Evenement[] $evenements
 * @property Collection|JeuFavori[] $jeufavoris
 *
 * @package App\Models
 */
class Jeu extends Model
{
	protected $table = 'jeu';
	protected $primaryKey = 'idJeu';
	public $timestamps = false;

	protected $fillable = [
		'nom',
		'jaquette'
	];

    public static function rules()
    {
        return [
            'nom' => 'required|string|max:255',
            'jaquette' => 'nullable|string|max:255'
        ];
    }

	public function evenements()
	{
		return $this->hasMany(Evenement::class, 'idJeu');
	}

	public function jeufavoris()
	{
		return $this->hasMany(JeuFavori::class, 'idJeu');
	}
}
