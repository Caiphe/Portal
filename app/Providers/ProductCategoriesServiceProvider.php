<?php

namespace App\Providers;

use App\Product;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ProductCategoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $productCategories = Product::isPublic()->orderBy('category')->get()->pluck('category')->unique();

        \View::share('productCategories', $productCategories);
    }
}
