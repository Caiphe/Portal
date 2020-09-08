<?php

namespace App\Http\Controllers;

use App\Category;
use App\Faq;

class FaqController extends Controller
{
	public function index()
	{
		$categories = Category::with('faqs:id,category_cid,question,answer,slug')->whereHas('faqs')->get();
		$faqs = call_user_func_array('array_merge', array_column($categories->toArray(), 'faqs'));

		return view('templates.faq.index', [
			'categories' => $categories,
			'faqs' => $faqs,
		]);
	}

	public function show(Faq $faq)
	{
		$categories = Category::with('faqs:id,category_cid,question,answer,slug')->whereHas('faqs')->get();
		$faqs = call_user_func_array('array_merge', array_column($categories->toArray(), 'faqs'));

		return view('templates.faq.index', [
			'categories' => $categories,
			'faqs' => $faqs,
			'faq' => $faq,
		]);
	}
}
