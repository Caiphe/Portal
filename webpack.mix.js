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

mix.scripts(
    ["resources/js/scripts.js", "resources/js/components/alert.js"],
    "public/js/scripts.js"
).scripts(
    ["resources/js/scripts.js", "resources/js/components/alert.js", "resources/js/templates/admin/scripts.js"],
    "public/js/templates/admin/scripts.js"
)
    .scripts("resources/js/templates/user/show.js", "public/js/templates/user/show.js")
    .scripts("resources/js/components/carousel.js", "public/js/components/carousel.js")
    .scripts("resources/js/components/sidebar-accordion.js", "public/js/components/sidebar-accordion.js")
    .scripts("resources/js/components/multiselect.js", "public/js/components/multiselect.js")
    .scripts("resources/js/components/select.js", "public/js/components/select.js")
    .scripts("resources/js/templates/products/index.js", "public/js/templates/products/index.js")
    .scripts("resources/js/templates/products/show.js", "public/js/templates/products/show.js")
    .scripts("resources/js/templates/bundles/index.js", "public/js/templates/bundles/index.js")
    .scripts("resources/js/templates/auth/register.js", "public/js/templates/auth/register.js")
    .scripts("resources/js/templates/auth/forgot-password.js", "public/js/templates/auth/forgot-password.js")
    .scripts("resources/js/templates/admin/products/edit.js", "public/js/templates/admin/products/edit.js")
    .scripts("resources/js/vendor/trix.js", "public/js/vendor/trix.js")
    .sass("resources/sass/auth/login.scss", "public/css/auth")
    .sass("resources/sass/auth/register.scss", "public/css/auth")
    .sass("resources/sass/auth/verify.scss", "public/css/auth")
    .sass("resources/sass/auth/forgot-password.scss", "public/css/auth")
    .sass("resources/sass/components/action_tab.scss", "public/css/components")
    .sass("resources/sass/components/carousel.scss", "public/css/components")
    .sass("resources/sass/components/multiselect.scss", "public/css/components")
    .sass("resources/sass/components/contact-form.scss", "public/css/components")
    .sass("resources/sass/components/card.scss", "public/css/components")
    .sass("resources/sass/components/card-link.scss", "public/css/components")
    .sass("resources/sass/components/card-icon.scss", "public/css/components")
    .sass("resources/sass/components/card-product.scss", "public/css/components")
    .sass("resources/sass/components/key-feature.scss", "public/css/components")
    .sass("resources/sass/components/sidebar-accordion.scss", "public/css/components")
    .sass("resources/sass/components/heading.scss", "public/css/components")
    .sass("resources/sass/components/card-search.scss", "public/css/components")
    .sass("resources/sass/components/pricing.scss", "public/css/components")
    .sass("resources/sass/templates/search.scss", "public/css/templates")
    .sass("resources/sass/templates/apps/index.scss", "public/css/templates/apps")
    .sass("resources/sass/templates/apps/create.scss", "public/css/templates/apps")
    .sass("resources/sass/templates/faq/index.scss", "public/css/templates/faq")
    .sass("resources/sass/templates/user/show.scss", "public/css/templates/user")
    .sass("resources/sass/templates/products/index.scss", "public/css/templates/products")
    .sass("resources/sass/templates/products/show.scss", "public/css/templates/products")
    .sass("resources/sass/templates/products/category.scss", "public/css/templates/products")
    .sass("resources/sass/templates/bundles/index.scss", "public/css/templates/bundles")
    .sass("resources/sass/templates/bundles/show.scss", "public/css/templates/bundles")
    .sass("resources/sass/templates/contact/index.scss", "public/css/templates/contact")
    .sass("resources/sass/templates/getting-started/index.scss", "public/css/templates/getting-started")
    .sass("resources/sass/templates/home.scss", "public/css/templates")
    .sass("resources/sass/templates/admin/products/edit.scss", "public/css/templates/admin/products/edit.css")
    .sass("resources/sass/templates/admin/index.scss", "public/css/templates/admin/index.css")
    .sass("resources/sass/components/_app.scss", "public/css/components")
    .sass("resources/sass/components/_panel.scss", "public/css/components")
    .sass("resources/sass/components/_accordion.scss", "public/css/components")
    .sass("resources/sass/components/_select.scss", "public/css/components")
    .sass("resources/sass/styles.scss", "public/css")
    .styles("resources/sass/vendor/trix.css", "public/css/vendor/trix.css")
    .version();
