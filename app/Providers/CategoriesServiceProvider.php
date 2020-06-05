<?php

namespace App\Providers;

use App\Category;
use Illuminate\Support\ServiceProvider;

class CategoriesServiceProvider extends ServiceProvider {
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
			$globalCategories = Category::whereHas('products', function($query){
				$query->isPublic();
			})->orWhereHas('bundles')->orderBy('title')->get();

			\View::share('globalCategories', $globalCategories);
		}
	}
}
