<?php

namespace App\Providers;

use App\Notification;
use Illuminate\Pagination\Paginator;
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
        Paginator::useBootstrap();

        /** Commented out due to not using the custom OpenApi viewer and using Stoplight Elements
        Blade::directive('reqType', function ($expression) {
            $options = explode(',', $expression);
            $value = trim($options[0], " '\"");
            $type = trim($options[1], " '\"");
            return <<<RTE
            <?php
                if($value !== ''){
                    switch ($type) {
                        case 'string':
                            echo '"' . $value . '"';
                            break;
                        case 'integer':
                            echo (int)$value;
                            break;
                        case 'float':
                            echo (float)$value;
                            break;
                        case 'boolean':
                            echo $value === 'true' ? 'true' : 'false';
                            break;
                        default:
                            echo $value;
                            break;
                    }
                } else {
                    echo [
                        'string' => '"string"',
                        'integer' => 1,
                        'float' => 1.00,
                        'boolean' => true,
                        'array' => '[]',
                        'object' => '{}',
                    ][$type] ?? '"string"';
                }
            ?>
            RTE;
        });
        **/

        Blade::directive('listFunc', function ($expression) {
            return "<?php echo \App\BladeHelpers::listFunc({$expression}); ?>";
        });

        Blade::directive('svg', function ($expression) {
            $options = explode(',', $expression);
            $icon = trim($options[0], " '\"");
            $colour = '#000000';
            $path = 'images/icons';

            if (isset($options[1])) {
                $colour = trim($options[1], " '\"");
            }

            if (isset($options[2])) {
                $path = trim($options[2], " '\"");
            }

            return <<<SVG
            <?php
                echo preg_replace('/<svg/', '<svg fill="$colour"', file_get_contents(public_path("$path/$icon.svg")));
            ?>
            SVG;
        });
    }
}
