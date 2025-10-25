<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class FavoriteGame extends Model
{
    use HasFactory;

    protected $table = 'favoritegame';
	public $timestamps = false;

	protected $casts = [
		'skillTypeId' => 'int',
		'gameId' => 'int',
		'userId' => 'int'
	];

	protected $fillable = [
		'description',
		'skillTypeId',
		'gameId',
		'userId'
	];

    public static function rules(): array
    {
        return [
            'description' => 'nullable|string',
            'skillTypeId' => 'required|integer|exists:skilltype,id',
            'gameId' => 'required|integer|exists:game,id',
            'userId' => 'required|integer|exists:user,id',
        ];
    }

	public function skilltype()
	{
		return $this->belongsTo(SkillType::class, 'skillTypeId');
	}

	public function game()
	{
		return $this->belongsTo(Game::class, 'gameId');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'userId');
	}
}
