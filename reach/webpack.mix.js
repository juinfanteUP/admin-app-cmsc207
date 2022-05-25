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

mix.js('resources/assets/js/app.js', 'public/assets/js/app.js')
    .vue()
    .combine(
  [
            'resources/assets/css/app.css',
            'resources/assets/css/bootstrap.css',
            'resources/assets/css/icons.css'
        ], 
    'public/assets/css/app.css'
    );
