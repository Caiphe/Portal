<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('svg', function ($expression) {
            $options = explode(', ', $expression);
            $icon = trim($options[0], " '\"");
            $icon = file_get_contents(public_path("images/icons/{$icon}.svg"));
            
            if (!isset($options[1])) return $icon;

            $colour = trim($options[1], " '\"");
            return preg_replace('/<svg/', '<svg fill="' . $colour . '"', $icon);
        });

        Blade::directive('allowonce', function ($expression) {
            $isDisplayed = '__allowonce_'.trim($expression, " '\"");
            return "<?php if(!isset(\$__env->{$isDisplayed})): \$__env->{$isDisplayed} = true; ?>";
        });
        Blade::directive('endallowonce', function ($expression) {
            return '<?php endif; ?>';
        });
    }
}
