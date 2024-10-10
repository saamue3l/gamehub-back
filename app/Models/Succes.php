<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Succes
 *
 * @property int $idSucces
 * @property string $nom
 * @property string|null $description
 *
 * @property Collection|SuccesObtenu[] $succesobtenus
 *
 * @package App\Models
 */
class Succes extends Model
{
	protected $table = 'succes';
	protected $primaryKey = 'idSucces';
	public $timestamps = false;

	protected $fillable = [
		'nom',
		'description'
	];

    public static function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000'
        ];
    }

	public function succesobtenus()
	{
		return $this->hasMany(SuccesObtenu::class, 'idSucces');
	}
}
