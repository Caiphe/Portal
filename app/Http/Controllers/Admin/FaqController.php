<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Faq;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        return view('templates.admin.faqs.index', [
            'faqs' => Faq::with('category')->paginate()
        ]);
    }

    public function edit(Faq $faq)
    {
        return view('templates.admin.faqs.edit', [
            'categories' => Category::pluck('title', 'cid'),
            'faq' => $faq
        ]);
    }

    public function update(Faq $faq, Request $request)
    {
        $faq->update($request->only(['question', 'answer', 'category_cid']));
        $faq->fresh();

        return redirect()->route('admin.faq.edit', $faq->slug)->with('alert', 'success:The faq has been updated.');
    }

    public function create()
    {
        return view('templates.admin.faqs.create', [
            'categories' => Category::pluck('title', 'cid')
        ]);
    }

    public function store(Request $request)
    {
        $faq = Faq::create($request->only(['question', 'answer', 'category_cid']));

        return redirect()->route('admin.faq.edit', $faq->slug)->with('alert', 'success:The faq has been created.');
    }
}
