const mix = require("laravel-mix");

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

mix.js("resources/js/scripts.js", "public/js")
    .version()
    .scripts("resources/js/components/multiselect.js", "public/js/components/multiselect.js")
    .sass("resources/sass/components/multiselect.scss", "public/css/components")
    .sass("resources/sass/styles.scss", "public/css")
    .version();
