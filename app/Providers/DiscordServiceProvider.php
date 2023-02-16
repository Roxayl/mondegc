<?php

namespace Roxayl\MondeGC\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Roxayl\MondeGC\Services\DiscordWebhookService;

class DiscordServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(
            DiscordWebhookService::class,
            /**
             * @param  Application  $app
             * @param  array  $parameters 'webhookName': L'identifiant du webhook à utiliser.
             * @return DiscordWebhookService
             */
            function(Application $app, array $parameters) {
                $useDebugChannel = (config('app.debug') && app()->environment() !== 'production')
                    || ! array_key_exists('webhookName', $parameters);

                if($useDebugChannel) {
                    $webhookName = 'debug';
                } else {
                    $webhookName = $parameters['webhookName'];
                }

                $webhookUrl = config("discord.webhookUrl.$webhookName");

                return new DiscordWebhookService($webhookUrl);
            }
        );
    }
}
