const mix = require('laravel-mix');
let webpack = require('webpack')
require('dotenv').config()

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

 let dotenvplugin = new webpack.DefinePlugin({
    'process.env': {
        APP_NAME: JSON.stringify(process.env.APP_NAME || 'Reach App'),
        APP_URL: JSON.stringify(process.env.APP_URL || ''),
        SOCKET_SERVER_URL: JSON.stringify(process.env.SOCKET_SERVER_URL || ''),
        SOCKET_LIB_URL: JSON.stringify(process.env.SOCKET_LIB_URL || '')
    }
});


mix.webpackConfig({ plugins: [ dotenvplugin ] });
mix
    .js('resources/assets/js/app.js', 'public/assets/js/app.js')
    .js('resources/assets/js/auth.js', 'public/assets/js/auth.js')
    .js('resources/assets/js/widget.js', 'public/widget/widget.js')
    .vue()
    .combine(
    [
            'resources/assets/css/app.css',
            'resources/assets/css/bootstrap.css',
            'resources/assets/css/icons.css'
        ], 
    'public/assets/css/app.css'
    );
