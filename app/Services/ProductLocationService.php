<?php

namespace App\Services;

use App\Country;
use App\Product;

class ProductLocationService
{
    public function fetch()
    {
        $products = Product::all()
            ->sortBy('category')
            ->groupBy('category');

        $productLocations = Product::isPublic()
            ->whereNotNull('locations')
            ->select('locations')
            ->get()
            ->implode('locations', ',');

        $productLocations = array_unique(explode(',', $productLocations));

        $filteredCountries = Country::whereIn('code', $productLocations)->get();

        $countries = $filteredCountries->each(function ($query) {
            return $query;
        })->pluck('name', 'code');

        return array($products, $countries);
    }
}
