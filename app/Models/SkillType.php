<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


class SkillType extends Model
{
	protected $table = 'skilltype';
	public $timestamps = false;

	protected $fillable = [
		'label'
	];

    public static function rules(): array
    {
        return [
            'label' => 'required|string|max:100',
        ];
    }

    public function favoritegames()
	{
		return $this->hasMany(FavoriteGame::class, 'skillTypeId');
	}
}
