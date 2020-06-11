<?php

namespace App\Services;

use App\Country;
use App\Product;

class ProductLocationService
{
	public function fetch(array $pids = [], $pluck = "")
	{
		$products = empty($pids) ?
			Product::with('category')->isPublic()->get() :
			Product::with('category')->findMany($pids);

		$countryCodes = $products->pluck('locations')->implode(',');

		$products = $products->sortBy('category.title')
			->groupBy('category.title');

		$countries = Country::whereIn('code', explode(',', $countryCodes))->pluck('name', 'code');

		if ($pluck === 'countries') {
			return $countries;
		} else if ($pluck === 'products') {
			return $products;
		}

		return array($products, $countries);
	}
}
