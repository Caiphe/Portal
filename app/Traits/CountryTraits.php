<?php

namespace App\Traits;

use App\Country;
use App\Product;

trait CountryTraits
{
    public function getCountry()
    {
        $locations = Product::isPublic()
            ->WhereNotNull('locations')
            ->Where('locations', '!=', 'all')
            ->select('locations')
            ->get()
            ->implode('locations', ',');

        $locations = array_unique(explode(',', $locations));
        $countries = Country::whereIn('code', $locations)->orderBy('name')->pluck('name', 'code');

        return $countries;
    }
}
