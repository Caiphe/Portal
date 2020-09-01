<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return Product::basedOnUser($request->user())->get();
            
        return view('templates.admin.home', [
            'products' => Product::basedOnUser($request->user())->get()
        ]);
    }
}
