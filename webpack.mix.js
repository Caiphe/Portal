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
    .scripts("resources/js/components/carousel.js", "public/js/components/carousel.js")
    .sass("resources/sass/components/card.scss", "public/css/components")
    .sass("resources/sass/components/card-link.scss", "public/css/components")
    .sass("resources/sass/components/key-feature.scss", "public/css/components")
    .sass("resources/sass/styles.scss", "public/css")
    .version();
