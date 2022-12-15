<?php

namespace Roxayl\MondeGC\Providers;

use App;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

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
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
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
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        $prefix = $this->getPrefix();

        Route::prefix($prefix)
             ->middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "legacy" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapLegacyRoutes()
    {
        // On ne préfixe pas les URLs vers le site legacy.
        Route::middleware('legacy')
             ->namespace($this->namespace . '\Legacy')
             ->group(base_path('routes/legacy.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
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
