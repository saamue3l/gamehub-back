<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StatutPost
 *
 * @property int $idStatutPost
 * @property string $libelle
 *
 * @property Collection|Post[] $posts
 *
 * @package App\Models
 */
class StatutPost extends Model
{
	protected $table = 'statutpost';
	protected $primaryKey = 'idStatutPost';
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

	public function posts()
	{
		return $this->hasMany(Post::class, 'idStatutPost');
	}
}
