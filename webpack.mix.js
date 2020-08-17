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


mix.js(['resources/js/admin/admin.js'], 'public/js')
    .sass('resources/sass/admin/admin.scss', 'public/css');

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

if (mix.inProduction()) {
    mix.version();
}
