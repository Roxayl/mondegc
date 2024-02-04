<?php

namespace Roxayl\MondeGC\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Reliese\Coders\CodersServiceProvider;
use YlsIdeas\FeatureFlags\Facades\Features;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if($this->app->environment() === 'local') {
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
