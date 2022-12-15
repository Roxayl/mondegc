<?php

namespace Roxayl\MondeGC\Providers;

use Roxayl\MondeGC\Services\DiscordWebhookService;
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
        $this->app->bind(DiscordWebhookService::class,
            /**
             * @param $app
             * @param array $parameters [0]: L'identifiant du webhook à utiliser.
             * @return DiscordWebhookService
             */
            function($app, array $parameters) {
                if(
                    (config('app.debug') && app()->environment() !== 'production') ||
                    empty($parameters))
                {
                    $key = 'debug';
                    $webhookUrl = config("discord.webhookUrl.$key");
                } else {
                    $webhookUrl = array_values($parameters)[0];
                }

                return new DiscordWebhookService($webhookUrl);
            });
    }
}
