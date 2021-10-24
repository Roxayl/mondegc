<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Notification
 *
 * @property int $id
 * @property int|null $recipient_id
 * @property string $type_notif
 * @property int|null $element
 * @property bool $unread
 * @property Carbon $created
 * @property CustomUser $user
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationLegacy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationLegacy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationLegacy query()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationLegacy whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationLegacy whereElement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationLegacy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationLegacy whereRecipientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationLegacy whereTypeNotif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationLegacy whereUnread($value)
 * @mixin Model
 */
class NotificationLegacy extends Model
{
	protected $table = 'notifications_legacy';
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

	public function user(): BelongsTo
	{
		return $this->belongsTo(CustomUser::class, 'recipient_id');
	}
}
