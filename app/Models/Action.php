<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Action
 *
 * @property int $idAction
 * @property int $xpGagne
 * @property string $typeAction
 *
 * @property Collection|HistoriqueAction[] $historiqueactions
 *
 * @package App\Models
 */
class Action extends Model
{
	protected $table = 'action';
	protected $primaryKey = 'idAction';
	public $timestamps = false;

	protected $casts = [
		'xpGagne' => 'int'
	];

	protected $fillable = [
		'xpGagne',
		'typeAction'
	];

    public static function rules()
    {
        return [
            'xpGagne' => 'required|integer',
            'typeAction' => 'required|string|max:255'
        ];
    }

	public function historiqueactions()
	{
		return $this->hasMany(HistoriqueAction::class, 'idAction');
	}
}
