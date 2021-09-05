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
        $this->app->bind(DiscordWebhookService::class, function($app) {
            $key = config('app.debug') ? 'debug' : 'ocgc';
            $webhookUrl = config("discord.webhookUrl.$key");
            return new DiscordWebhookService($webhookUrl);
        });
    }
}
