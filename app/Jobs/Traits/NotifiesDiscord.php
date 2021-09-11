<?php

namespace App\Jobs\Traits;

use App\Models\DiscordNotification;

trait NotifiesDiscord
{
    /**
     * @inheritDoc
     */
    public function getWebhookName(): string
    {
        if(property_exists($this, $this->webhookName)) {
            return $this->webhookName;
        } else {
            if($value = config('discord.webhookUrl.debug')) {
                return $value;
            } else {
                $key = array_key_first(config('discord.webhookUrl'));
                return config("discord.webhookUrl.$key");
            }
        }
    }

    public function handle(): void
    {
        /** @var \App\Jobs\Contracts\NotifiesDiscord $this */
        DiscordNotification::fetch($this)->send();
    }
}
