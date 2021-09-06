<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Jobs\Contracts\NotifiesDiscord;
use App\Services\DiscordWebhookService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class DiscordNotification
 * 
 * @property int $id
 * @property string $channel
 * @property string $type
 * @property string $model_identifier
 * @property string $uuid
 * @property array $payload
 * @property boolean $is_sent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class DiscordNotification extends Model
{
    protected $table = 'discord_notifications';

    protected $fillable = [
        'channel',
        'type',
        'model_identifier',
        'uuid',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'is_sent' => 'bool',
    ];

    protected $attributes = [
        'is_sent' => false,
    ];

    public ?NotifiesDiscord $notification = null;

    private DiscordWebhookService $webhook;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->webhook = resolve(DiscordWebhookService::class);
    }

    public static function fetch(NotifiesDiscord $notification): DiscordNotification
    {
        $webhook = app()->make(DiscordWebhookService::class);

        if($notification->isUnique()) {
            $model = $notification->getModelIdentifier();
            $primaryKey = $model->primaryKey;
            $find = DiscordNotification
                ::where('channel', $webhook->getWebhookUrl())
                ->where('type', get_class($notification))
                ->where('model_identifier', $model->$primaryKey)
                ->first();
            if($find && ($find instanceof DiscordNotification)) {
                return $find;
            }
        }

        $discordNotification = new DiscordNotification();

        $discordNotification->notification = $notification;

        $discordNotification->channel = $discordNotification->webhook->getWebhookUrl();
        $discordNotification->type    = get_class($discordNotification->notification);
        $discordNotification->uuid    = Str::uuid();
        $discordNotification->payload = $discordNotification->notification->generatePayload();

        $model = $discordNotification->notification->getModelIdentifier();
        $primaryKey = $model->primaryKey;
        $discordNotification->model_identifier = $model->$primaryKey;

        return $discordNotification;
    }

    public function isUnique(): bool
    {
        return $this->notification->isUnique();
    }

    public function send()
    {
        $this->save();
        if($this->is_sent) return;

        rescue(function() {
            $this->webhook->sendMessage($this->payload);
            $this->is_sent = true;
            $this->save();
        }, null, config('app.debug'));
    }
}
