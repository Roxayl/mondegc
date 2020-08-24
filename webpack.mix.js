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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.scripts([
    'assets/js/jquery.js',
    'assets/js/bootstrap.js',
    'assets/js/bootstrap-affix.js',
    'assets/js/bootstrap-scrollspy.js',
    'assets/js/bootstrapx-clickover.js',
    'assets/js/bootstrap-modalmanager.js',
    'assets/js/bootstrap-modal.js',
], 'public/js/vendor-compiled.js');

mix.scripts(['assets/js/application.js'], 'public/js/application-compiled.js');

mix.scripts([
    'assets/js/Chart.2.7.3.bundle.js',
    'assets/js/d3.v4.min.js',
    'assets/js/d3-parliament.js',
], 'public/js/vendor-parliament-compiled.js');

mix.scripts([
    'resources/js/legacy/parliament.js',
], 'public/js/parliament-compiled.js');

if (mix.inProduction()) {
    mix.version();
}
