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
            $options = explode(',', $expression);
            $icon = trim($options[0], " '\"");
            $colour = '#000000';
            $path = 'images/icons/';
            
            if(isset($options[1])){
                $colour = trim($options[1], " '\"");
            }
            
            if(isset($options[2])){
                $path = trim($options[2], " '\"");
            }

            return <<<SVG
            <?php
                echo preg_replace('/<svg/', '<svg fill="$colour"', file_get_contents(public_path("$path/$icon.svg")));
            ?>
            SVG;
        });

        Blade::directive('allowonce', function ($expression) {
            $isDisplayed = '__allowonce_'.trim($expression, " '\"");
            return "<?php if(!isset(\$__env->{$isDisplayed})): \$__env->{$isDisplayed} = true; ?>";
        });
        Blade::directive('endallowonce', function ($expression) {
            return '<?php endif; ?>';
        });

        Blade::directive('pushscript', function ($expression) {
            $isDisplayed = '__pushscript_'.trim($expression, " '\"");
            return "<?php if(!isset(\$__env->{$isDisplayed})): \$__env->{$isDisplayed} = true; \$__env->startPush('scripts'); ?>";
        });
        Blade::directive('endpushscript', function ($expression) {
            return '<?php $__env->stopPush(); endif; ?>';
        });
    }
}