<?php

namespace Roxayl\MondeGC\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Roxayl\MondeGC\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapLegacyRoutes();
    }

    /**
     * Renvoie le préfixe de route, lorsque l'URL de base contient notamment un répertoire
     * (e.g. http://localhost/monde/).
     *
     * @return string
     */
    public function getPrefix(): string
    {
        $prefix = '';
        if(php_sapi_name() !== "cli") {
            $prefix = config('app.directory_path', '');
        }

        return $prefix;
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        $prefix = $this->getPrefix();

        Route::prefix($prefix)
             ->middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Définit les routes "legacy" de l'application.
     *
     * Cette route "par défaut" est utilisée lorsque l'application ne parvient pas à résoudre une route vers un
     * controller de l'app Laravel. Cette route "attrape-tout" permet de faire gérer la requête par le controller
     * dédié au site "legacy".
     */
    protected function mapLegacyRoutes(): void
    {
        // On ne préfixe pas les URLs vers le site legacy.
        Route::middleware('legacy')
             ->namespace($this->namespace . '\Legacy')
             ->group(function() {
                Route::any('/{path?}', 'LegacySiteController')->where('path', '.*');
             });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        $prefix = $this->getPrefix();
        if($prefix) {
            $prefix .= '/';
        }
        $prefix .= 'api';

        Route::prefix($prefix)
             ->middleware('api')
             ->namespace($this->namespace . '\Api')
             ->group(base_path('routes/api.php'));
    }
}
