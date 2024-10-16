<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TopicStatus
 *
 * @property int $topicStatusId
 * @property string $label
 *
 * @property Collection|Topic[] $topics
 *
 * @package App\Models
 */
class TopicStatus extends Model
{
	protected $table = 'topicstatus';
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


    public function topics()
	{
		return $this->hasMany(Topic::class, 'topicStatusId');
	}
}
