<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Platform extends Model
{
    use hasFactory;

	protected $table = 'platform';
	public $timestamps = false;

	protected $fillable = [
		'name',
        'logoUrl'
	];

    public static function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'logoUrl' => 'string'
        ];
    }

	public function usernames()
	{
		return $this->hasMany(Username::class, 'platformId');
	}
}
