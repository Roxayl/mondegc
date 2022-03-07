<?php

namespace App\Models;

use App\Jobs\Contracts\NotifiesDiscord;
use App\Services\DiscordWebhookService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
 * @method static Builder|DiscordNotification newModelQuery()
 * @method static Builder|DiscordNotification newQuery()
 * @method static Builder|DiscordNotification query()
 * @method static Builder|DiscordNotification whereChannel($value)
 * @method static Builder|DiscordNotification whereCreatedAt($value)
 * @method static Builder|DiscordNotification whereId($value)
 * @method static Builder|DiscordNotification whereIsSent($value)
 * @method static Builder|DiscordNotification whereModelIdentifier($value)
 * @method static Builder|DiscordNotification wherePayload($value)
 * @method static Builder|DiscordNotification whereType($value)
 * @method static Builder|DiscordNotification whereUpdatedAt($value)
 * @method static Builder|DiscordNotification whereUuid($value)
 * @mixin Model
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

    private ?DiscordWebhookService $webhook = null;

    /**
     * Récupère une instance de {@see DiscordNotification} à partir des informations du job.
     * @param NotifiesDiscord $notification
     * @return DiscordNotification
     */
    public static function fetch(NotifiesDiscord $notification): DiscordNotification
    {
        $webhook = self::resolveWebhook($notification);

        // Check if a record representing the notification described in the 'NotifiesDiscord" instance already exists.
        if($notification->isUnique()) {
            $model = $notification->getModelIdentifier();
            $primaryKey = $model->primaryKey;
            $find = DiscordNotification
                ::where('channel', $webhook->getWebhookUrl())
                ->where('type', get_class($notification))
                ->where('model_identifier', $model->$primaryKey)
                ->first();

            // If it exists, we return it, by updating its value with the notification data.
            if($find && ($find instanceof DiscordNotification)) {
                $find->populate($notification, $webhook); // We reuse the instance of the webhook previously resolved.
                return $find;
            }
        }

        // If a record is not found or this record is not unique, we create one and fill its properties.
        $discordNotification = new DiscordNotification();
        $discordNotification->populate($notification);

        return $discordNotification;
    }

    /**
     * Remplit les propriétés du modèle à partir des informations de l'objet {@see NotifiesDiscord}.
     * @param NotifiesDiscord $notification Job lié à une notification Discord.
     * @param DiscordWebhookService|null $webhook Si le webhook n'est pas passé en paramètre, une instance du webhook
     *                                            sera résolue à partir des informations de l'objet
     *                                            {@see $notification}.
     */
    private function populate(NotifiesDiscord $notification, ?DiscordWebhookService $webhook = null): void
    {
        $this->notification = $notification;

        // Use the passed webhook service instance. Else, instantiate one by using the parameters from the
        // NotifiesDiscord object.
        $this->webhook = $webhook ?? self::resolveWebhook($this->notification);

        $this->channel = $this->webhook->getWebhookUrl();
        $this->type    = get_class($this->notification);
        $this->uuid    = Str::uuid();
        $this->payload = $this->notification->generatePayload();

        $model = $this->notification->getModelIdentifier();
        $primaryKey = $model->primaryKey;
        $this->model_identifier = $model->$primaryKey;
    }

    /**
     * @param NotifiesDiscord $notification
     * @return DiscordWebhookService
     */
    private static function resolveWebhook(NotifiesDiscord $notification): DiscordWebhookService
    {
        return app()->make(DiscordWebhookService::class, [$notification->getWebhookName()]);
    }

    /**
     * Définit si la notification doit être unique, pour un channel, un type et un identifiant de modèle donné.
     * @see NotifiesDiscord::isUnique()
     * @return bool
     */
    public function isUnique(): bool
    {
        return $this->notification->isUnique();
    }

    /**
     * Persiste les informations du modèle, et envoie la notification via le webhook.
     */
    public function send(): void
    {
        if(is_null($this->webhook)) {
            throw new \UnexpectedValueException("Webhook instance is not defined.");
        }

        $this->save();
        if($this->is_sent) return;

        rescue(function() {
            $this->webhook->sendMessage($this->payload);
            $this->is_sent = true;
            $this->save();
        }, null, config('app.debug'));
    }
}
