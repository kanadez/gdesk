const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .scripts([
        'resources/js/ui/jquery.min.js',
        'resources/js/ui/jquery-ui.js',
        'resources/js/ui/jquery.mobile-events.min.js',
        'resources/js/ui/owl.carousel.js',
        'resources/js/ui/core.js',
        'resources/js/ui/modals.js',
    ], 'public/js/all.combined.js')
    .js('resources/js/app/ymaps-jquery.js', 'public/js')
    .copy('resources/fonts/', 'public/fonts')
    .copy('resources/images/', 'public/images')
    .sass('resources/css/all.combined.scss', 'public/css/all.combined.css')
    .vue()
    .version();
