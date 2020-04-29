<?php

namespace App\Services;

use App\Country;
use App\Product;

class ProductLocationService {
	public function fetch() {
		$products = Product::isPublic()->get();

		$countryCodes = $products->pluck('locations')->implode(',');

		$products = $products->sortBy('category')
			->groupBy('category');

		$countries = Country::whereIn('code', explode(',', $countryCodes))->pluck('name', 'code');

		return array($products, $countries);
	}
}
