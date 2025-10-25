<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Username extends Model
{
    use HasFactory;

    protected $table = 'username';
	public $timestamps = false;

	protected $casts = [
		'platformId' => 'int',
		'userId' => 'int'
	];

	protected $fillable = [
		'username',
		'platformId',
		'userId'
	];

    public static function rules(): array
    {
        return [
            'username' => 'required|string|max:50',
            'platformId' => 'required|integer|exists:platform,id',
            'userId' => 'required|integer|exists:user,id',
        ];
    }

    public function platform()
	{
		return $this->belongsTo(Platform::class, 'platformId');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'userId');
	}
}
