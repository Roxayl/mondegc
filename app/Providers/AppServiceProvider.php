<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Providers;

use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Reliese\Coders\CodersServiceProvider;
use Roxayl\MondeGC\Services\Discord\DiscordWebhookService;
use Roxayl\MondeGC\Services\FakerProviders;
use Roxayl\MondeGC\Services\StringBladeService;
use YlsIdeas\FeatureFlags\Facades\Features;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Generator::class, function (): Generator {
            $faker = Factory::create('fr_FR');
            $faker->addProvider(new FakerProviders\ChapterEntryMediaProvider($faker));
            $faker->addProvider(new FakerProviders\EventNameProvider($faker));

            return $faker;
        });

        $this->app->singleton(StringBladeService::class, function (Application $app): StringBladeService {
            return new StringBladeService(
                $app->make(Filesystem::class),
                $app->make(ViewFactory::class),
                $app->make(BladeCompiler::class)
            );
        });

        $this->app->bind(
            DiscordWebhookService::class,
            /**
             * @param  Application  $app
             * @param  array  $parameters  'webhookName': L'identifiant du webhook à utiliser.
             * @return DiscordWebhookService
             */
            function (Application $app, array $parameters): DiscordWebhookService {
                $useDebugChannel = (config('app.debug') && $app->environment() !== 'production')
                    || ! array_key_exists('webhookName', $parameters);

                if ($useDebugChannel) {
                    $webhookName = 'debug';
                } else {
                    $webhookName = $parameters['webhookName'];
                }

                $webhookUrl = config("discord.webhookUrl.$webhookName");

                return new DiscordWebhookService($webhookUrl);
            }
        );

        if ($this->app->environment() === 'local') {
            $this->app->register(CodersServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('blocks.pagination.bootstrap-2');

        Carbon::setLocale(app()->getLocale());

        Features::noScheduling();
    }
}
