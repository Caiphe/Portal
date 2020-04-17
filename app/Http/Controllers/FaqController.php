<?php

namespace App\Http\Controllers;

use App\Category;
use App\Faq;

class FaqController extends Controller
{
    public function index()
    {
		return view('templates.faq.index', [
		        'categories' => Category::all(),
		        'faqs' => Faq::all()
            ]
        );
    }
}
