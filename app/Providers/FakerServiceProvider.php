<?php

namespace Roxayl\MondeGC\Providers;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\ServiceProvider;
use Roxayl\MondeGC\Services\FakerProviders\ChapterEntryMediaProvider;
use Roxayl\MondeGC\Services\FakerProviders\EventNameProvider;

class FakerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(Generator::class, function () {
            $faker = Factory::create('fr_FR');
            $faker->addProvider(new ChapterEntryMediaProvider($faker));
            $faker->addProvider(new EventNameProvider($faker));
            return $faker;
        });
    }
}
