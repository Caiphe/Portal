<?php

namespace App\Providers;

use App\Category;
use Illuminate\Support\ServiceProvider;

class CategoriesServiceProvider extends ServiceProvider
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
		view()->composer(['components.header', 'templates.home'], function ($view) {
			$globalCategories = Category::whereHas('products', function ($query) {
				$query->basedOnUser(\Auth::user())->where('cid', '!=', 'misc');
			})->orWhereHas('bundles', function ($query) {
				$query->where('cid', '!=', 'misc');
			})->orderBy('title')->get();

			\View::share('globalCategories', $globalCategories);
		});
	}
}
