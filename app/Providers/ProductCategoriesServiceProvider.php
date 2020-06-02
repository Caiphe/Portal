<?php

namespace App\Providers;

use App\Category;
use Illuminate\Support\ServiceProvider;

class ProductCategoriesServiceProvider extends ServiceProvider {
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot() {
		if (\Schema::hasTable('products')) {
			$globalCategories = Category::whereHas('products')->orWhereHas('bundles')->orderBy('title')->get()->pluck('title', 'slug');

			\View::share('globalCategories', $globalCategories);
		}
	}
}
