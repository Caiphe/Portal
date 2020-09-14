<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Faq;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $faq = Faq::with('category');

        if ($request->has('q')) {
            $query = "%" . $request->q . "%";
            $faq->where(function ($q) use ($query) {
                $q->where('question', 'like', $query)
                    ->orWhere('answer', 'like', $query);
            });
        }

        if ($request->ajax()) {
            return response()
                ->view('components.admin.table-data', [
                    'collection' => $faq->paginate(),
                    'fields' => ['question', 'category.title'],
                    'modelName' => 'faq'
                ], 200)
                ->header('Content-Type', 'text/html');
        }

        return view('templates.admin.faqs.index', [
            'faqs' => $faq->paginate()
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

        return redirect()->route('admin.faq.index')->with('alert', 'success:The faq has been updated.');
    }

    public function create()
    {
        return view('templates.admin.faqs.create', [
            'categories' => Category::pluck('title', 'cid')
        ]);
    }

    public function store(Request $request)
    {
        Faq::create($request->only(['question', 'answer', 'category_cid']));

        return redirect()->route('admin.faq.index')->with('alert', 'success:The faq has been created.');
    }

    public function destroy(Request $request, Faq $faq)
    {
        $faq->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'body' => 'The faq has been deleted.']);
        }

        return redirect()->route('admin.faq.index')->with('alert', 'success:The faq has been deleted.');
    }
}
