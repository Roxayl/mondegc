<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\CustomUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Log
 * 
 * @property int $id
 * @property string $target
 * @property int|null $target_id
 * @property string $type_action
 * @property int|null $user_id
 * @property string|null $data_changes
 * @property Carbon $created
 * 
 * @property CustomUser $user
 *
 * @package App\Models
 */
class Log extends Model
{
	protected $table = 'log';
	public $timestamps = false;

	protected $casts = [
		'target_id' => 'int',
		'user_id' => 'int'
	];

	protected $dates = [
		'created'
	];

	protected $fillable = [
		'target',
		'target_id',
		'type_action',
		'user_id',
		'data_changes',
		'created'
	];

	public function user()
	{
		return $this->belongsTo(CustomUser::class);
	}
}
