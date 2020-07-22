<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Notification
 * 
 * @property int $id
 * @property int|null $recipient_id
 * @property string $type_notif
 * @property int|null $element
 * @property bool $unread
 * @property Carbon $created
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Notification extends Model
{
	protected $table = 'notifications';
	public $timestamps = false;

	protected $casts = [
		'recipient_id' => 'int',
		'element' => 'int',
		'unread' => 'bool'
	];

	protected $dates = [
		'created'
	];

	protected $fillable = [
		'recipient_id',
		'type_notif',
		'element',
		'unread',
		'created'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'recipient_id');
	}
}
