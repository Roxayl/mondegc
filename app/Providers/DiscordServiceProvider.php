<?php

namespace App\Providers;

use App\Services\DiscordWebhookService;
use Illuminate\Support\ServiceProvider;

class DiscordServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(/**
         * @param $app
         * @param array $parameters L'identifiant du webhook à utiliser.
         * @return DiscordWebhookService
         */ DiscordWebhookService::class, function($app, array $parameters) {
            $key = config('app.debug') || empty($parameters) ? 'debug' : $parameters[0];
            $webhookUrl = config("discord.webhookUrl.$key");
            return new DiscordWebhookService($webhookUrl);
        });
    }
}
