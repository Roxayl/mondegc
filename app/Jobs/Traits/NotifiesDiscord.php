<?php

namespace App\Jobs\Traits;

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
                $key = array_key_first(config('discord.webhookName'));
                return config("discord.webhookUrl.$key");
            }
        }
    }
}
