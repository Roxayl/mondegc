<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Notification.
 *
 * @property int $id
 * @property int|null $recipient_id
 * @property string $type_notif
 * @property int|null $element
 * @property bool $unread
 * @property Carbon $created
 * @property CustomUser $user
 *
 * @method static Builder|NotificationLegacy newModelQuery()
 * @method static Builder|NotificationLegacy newQuery()
 * @method static Builder|NotificationLegacy query()
 * @method static Builder|NotificationLegacy whereCreated($value)
 * @method static Builder|NotificationLegacy whereElement($value)
 * @method static Builder|NotificationLegacy whereId($value)
 * @method static Builder|NotificationLegacy whereRecipientId($value)
 * @method static Builder|NotificationLegacy whereTypeNotif($value)
 * @method static Builder|NotificationLegacy whereUnread($value)
 *
 * @mixin \Eloquent
 */
class NotificationLegacy extends Model
{
    protected $table = 'notifications_legacy';
    public $timestamps = false;

    protected $casts = [
        'recipient_id' => 'int',
        'element' => 'int',
        'unread' => 'bool',
    ];

    protected $dates = [
        'created',
    ];

    protected $fillable = [
        'recipient_id',
        'type_notif',
        'element',
        'unread',
        'created',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(CustomUser::class, 'recipient_id');
    }
}
