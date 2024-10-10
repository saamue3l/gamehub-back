<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 *
 * @property int $idRole
 * @property string $libelle
 *
 * @property Collection|Utilisateur[] $utilisateurs
 *
 * @package App\Models
 */
class Role extends Model
{
	protected $table = 'role';
	protected $primaryKey = 'idRole';
	public $timestamps = false;

	protected $fillable = [
		'libelle'
	];

    public static function rules()
    {
        return [
            'libelle' => 'required|string|max:255'
        ];
    }

	public function utilisateurs()
	{
		return $this->hasMany(Utilisateur::class, 'idRole');
	}
}
