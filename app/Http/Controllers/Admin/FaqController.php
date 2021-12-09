<?php

namespace App\Http\Controllers\Admin;

use App\Faq;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqRequest;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $sort = '';
        $order = $request->get('order', 'desc');
        $faq = Faq::with('category')
            ->select([
                'faqs.*',
                'categories.title as category_title'
            ])->join('categories', 'faqs.category_cid', '=', 'categories.cid')
            ->when($request->has('q'), function ($q) use ($request) {
                $query = "%" . $request->q . "%";
                $q->where('question', 'like', $query)->orWhere('answer', 'like', $query);
            });

        if ($request->has('sort')) {
            $sort = $request->get('sort');

            if ($sort === 'category.title') {
                $faq->orderBy('category_title', $order);
            } else {
                $faq->orderBy($sort, $order);
            }

            $order = ['asc' => 'desc', 'desc' => 'asc'][$order] ?? 'desc';
        }

        if ($request->ajax()) {
            return response()
                ->view('components.admin.list', [
                    'collection' => $faq->paginate(),
                    'order' => $order,
                    'fields' => ['Question' => 'question', 'Category' => 'category.title'],
                    'modelName' => 'faq'
                ], 200)
                ->header('Vary', 'X-Requested-With')
                ->header('Content-Type', 'text/html');
        }

        return view('templates.admin.faqs.index', [
            'faqs' => $faq->paginate(),
            'order' => $order,
        ]);
    }

    public function edit(Faq $faq)
    {
        return view('templates.admin.faqs.edit', [
            'categories' => Category::pluck('title', 'cid'),
            'faq' => $faq
        ]);
    }

    public function update(Faq $faq, FaqRequest $request)
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

    public function store(FaqRequest $request)
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
