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

mix.scripts('resources/js/scripts.js', 'public/js/scripts.js')
    .scripts("resources/js/components/carousel.js", "public/js/components/carousel.js")
    .scripts("resources/js/components/sidebar-accordion.js", "public/js/components/sidebar-accordion.js")
    .scripts("resources/js/components/multiselect.js", "public/js/components/multiselect.js")
    .sass("resources/sass/components/carousel.scss", "public/css/components")
    .sass("resources/sass/components/multiselect.scss", "public/css/components")
    .sass("resources/sass/components/card.scss", "public/css/components")
    .sass("resources/sass/components/card-link.scss", "public/css/components")
    .sass("resources/sass/components/card-product.scss", "public/css/components")
    .sass("resources/sass/components/key-feature.scss", "public/css/components")
    .sass("resources/sass/components/sidebar-accordion.scss", "public/css/components")
    .sass("resources/sass/components/heading.scss", "public/css/components")
    .sass("resources/sass/templates/getting-started/index.scss", "public/css/templates/getting-started")
    .sass("resources/sass/templates/getting-started/show.scss", "public/css/templates/getting-started")
    .sass("resources/sass/styles.scss", "public/css")
    .version();