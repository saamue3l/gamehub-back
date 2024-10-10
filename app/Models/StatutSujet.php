<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StatutSujet
 *
 * @property int $idStatutSujet
 * @property string $libelle
 *
 * @property Collection|Sujet[] $sujets
 *
 * @package App\Models
 */
class StatutSujet extends Model
{
	protected $table = 'statutsujet';
	protected $primaryKey = 'idStatutSujet';
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

	public function sujets()
	{
		return $this->hasMany(Sujet::class, 'idStatutSujet');
	}
}
