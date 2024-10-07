const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

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

/**
mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/css/app.css', 'public/css');
 **/
mix.js('resources/js/app.js', 'public/js')
    .vue() // Ativando o suporte para Vue.js
    .postCss('resources/css/app.css', 'public/css', [
        tailwindcss('tailwind.config.js'), // Adiciona suporte ao Tailwind CSS
    ]);

mix.copyDirectory('resources/img', 'public/img');
