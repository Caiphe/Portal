<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Bundle;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return view('templates.home',
        [
            'productsCollection' => Product::isPublic()->get()->pluck('category.title')->unique()->take(6),
            'bundleCollection' => Bundle::all()->take(4)
        ]);
    }
}
