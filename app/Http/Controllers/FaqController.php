<?php

namespace App\Http\Controllers;

use App\Category;

class FaqController extends Controller
{
    public function index()
    {
		return view('templates.faq.index', [
		        'categories' => Category::with('faqs')->get()
            ]
        );
    }
}
