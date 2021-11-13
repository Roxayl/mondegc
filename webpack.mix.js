const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

 /*
 |--------------------------------------------------------------------------
 | Assets legacy globaux compilés pour le front-end Laravel
 |--------------------------------------------------------------------------
 |
 | Ces assets sont chargés depuis le layout et sont disponibles dans toutes
 | les pages servies par le front-end Laravel.
 |
 */

// Compiler les libraries JS (jQuery, Bootstrap core, et plugins Bootstrap)
mix.scripts([
    'assets/js/jquery.js',
    'assets/js/bootstrap.js',
    'assets/js/bootstrap-affix.js',
    'assets/js/bootstrap-scrollspy.js',
    'assets/js/bootstrapx-clickover.js',
    'assets/js/bootstrap-modalmanager.js',
    'assets/js/bootstrap-modal.js',
], 'public/js/vendor-compiled.js');

// Compiler les assets de l'application.
mix.scripts([
    'assets/js/application.js',
    'resources/js/component-loader.js',
], 'public/js/application-compiled.js');

 /*
 |--------------------------------------------------------------------------
 | Assets spécifiques pour certaines pages pour le front-end Laravel
 |--------------------------------------------------------------------------
 |
 | Ces assets sont chargés depuis le layout et sont disponibles dans toutes
 | les pages servies par le front-end Laravel.
 |
 */

mix.scripts([
    'assets/js/Chart.2.7.3.bundle.js',
    'assets/js/d3.v4.min.js',
    'assets/js/d3-parliament.js',
], 'public/js/vendor-parliament-compiled.js');

mix.scripts([
    'resources/js/parliament.js',
], 'public/js/parliament-compiled.js');

// Rajouter le numéro de version à la fin des URLs, dans l'environnement de production.
if (mix.inProduction()) {
    mix.version();
}
