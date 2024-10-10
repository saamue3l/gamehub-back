<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TypeCompetence
 *
 * @property int $idTypeCompetence
 * @property string $libelle
 *
 * @property Collection|JeuFavori[] $jeufavoris
 *
 * @package App\Models
 */
class TypeCompetence extends Model
{
	protected $table = 'typecompetence';
	protected $primaryKey = 'idTypeCompetence';
	public $timestamps = false;

	protected $fillable = [
		'libelle'
	];

    public static function rules() : array
    {
        return [
            'libelle' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

	public function jeufavoris()
	{
		return $this->hasMany(JeuFavori::class, 'idTypeCompetence');
	}
}
