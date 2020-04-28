<?php

namespace App\Http\Controllers;

use App\Category;

class FaqController extends Controller
{
	public function index() {
		$categories = Category::with('faqs:id,category_id,question,answer')->whereHas('faqs')->get();
		$categoryLookup = array_map('strtolower', $categories->pluck('title', 'id')->toArray());
		$faqs = call_user_func_array('array_merge', array_column($categories->toArray(), 'faqs'));

            return view('templates.faq.index', [
                'categories' => $categories,
                'categoryLookup' => $categoryLookup,
                'faqs' => $faqs,
            ]
		);
	}
}
