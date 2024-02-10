<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Jobs\Traits;

use Roxayl\MondeGC\Models\DiscordNotification;

trait NotifiesDiscord
{
    public function getWebhookName(): string
    {
        if (property_exists($this, 'webhookName')) {
            $webhookName = $this->webhookName;
        } else {
            if (config('discord.webhookUrl.debug')) {
                $webhookName = 'debug';
            } else {
                $webhookName = array_key_first(config('discord.webhookUrl'));
            }
        }

        return $webhookName;
    }

    public function handle(): void
    {
        /** @var \Roxayl\MondeGC\Jobs\Contracts\NotifiesDiscord $this */
        DiscordNotification::fetch($this)->send();
    }
}
