<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $sort = '';
        $order = $request->get('order', 'desc');
        $numberPerPage = (int)$request->get('number_per_page', '15');
        $categories = Category::when($request->has('q'), function ($q) use ($request) {
            $query = "%" . $request->q . "%";
            $q->where(function ($q) use ($query) {
                $q->where('title', 'like', $query)
                    ->orWhereHas('content', function ($q) use ($query) {
                        $q->where('title', 'like', $query)
                            ->orWhere('body', 'like', $query);
                    });
            });
        });

        if ($request->has('sort')) {
            $sort = $request->get('sort');
            $categories->orderBy($sort, $order);
            $order = ['asc' => 'desc', 'desc' => 'asc'][$order] ?? 'desc';
        }

        if ($request->ajax()) {
            return response()
                ->view('components.admin.list', [
                    'collection' => $categories->paginate($numberPerPage),
                    'order' => $order,
                    'fields' => ['Title' => 'title', 'Theme' => 'theme|addClass:not-on-mobile'],
                    'modelName' => 'category'
                ], 200)
                ->header('Vary', 'X-Requested-With')
                ->header('Content-Type', 'text/html');
        }

        return view('templates.admin.categories.index', [
            'categories' => $categories->paginate($numberPerPage),
            'order' => $order,
        ]);
    }

    public function edit(Category $category)
    {
        $category->load('content');

        return view('templates.admin.categories.edit', [
            'category' => $category,
            'content' => $category->content->groupBy('type')
        ]);
    }

    public function update(Category $category, CategoryRequest $request)
    {
        $now = date('Y-m-d H:i:s');
        $categoryData = $request->only(['title', 'theme']);
        $categoryData['description'] = $request->input('heading-title', '');
        $updateRelationships = $category->title;
        $relationships = [];

        if ($updateRelationships) {
            $category->load(['faqs', 'products', 'bundles']);
            $relationships = $category->getRelations();

            foreach ($relationships as $type => $collection) {
                $collection->each(fn ($faq) => $faq->update(['category_cid' => 'misc']));
            }
        }

        $category->update($categoryData);
        $category->content()->delete();
        $category->content()->createMany([
            [
                'title' => $request->get('heading-title', '') ?? '',
                'slug' => $category->slug . '-heading',
                'type' => 'heading',
                'body' => $request->get('heading-body', '') ?? '',
                'published_at' => $now
            ],
            [
                'title' => $request->get('benefits-title', '') ?? '',
                'slug' => $category->slug . '-benefits',
                'type' => 'benefits',
                'body' => $request->get('benefits-body', '') ?? '',
                'published_at' => $now
            ],
            [
                'title' => $request->get('developer-centric-title', '') ?? '',
                'slug' => $category->slug . '-developer-centric',
                'type' => 'developer-centric',
                'body' => $request->get('developer-centric-body', '') ?? '',
                'published_at' => $now
            ],
            [
                'title' => $request->get('bundles-title', '') ?? '',
                'slug' => $category->slug . '-bundles',
                'type' => 'bundles',
                'body' => $request->get('bundles-body', '') ?? '',
                'published_at' => $now
            ],
            [
                'title' => $request->get('products-title', '') ?? '',
                'slug' => $category->slug . '-products',
                'type' => 'products',
                'body' => $request->get('products-body', '') ?? '',
                'published_at' => $now
            ],
        ]);

        $category->fresh();

        if ($updateRelationships) {
            foreach ($relationships as $type => $collection) {
                $collection->each(fn ($faq) => $faq->update(['category_cid' => $category->cid]));
            }
        }

        return redirect()->route('admin.category.index')->with('alert', 'success:The category has been updated.');
    }

    public function create()
    {
        return view('templates.admin.categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $now = date('Y-m-d H:i:s');
        $categoryData = $request->only(['title', 'theme']);
        $categoryData['description'] = $request->input('heading-title', '');

        $category = Category::create($categoryData);
        $category->content()->createMany([
            [
                'title' => $request->input('heading-title', '') ?? '',
                'slug' => $category->slug . '-heading',
                'type' => 'heading',
                'body' => $request->input('heading-body', '') ?? '',
                'published_at' => $now
            ],
            [
                'title' => $request->input('benefits-title', '') ?? '',
                'slug' => $category->slug . '-benefits',
                'type' => 'benefits',
                'body' => $request->input('benefits-body', '') ?? '',
                'published_at' => $now
            ],
            [
                'title' => $request->input('developer-centric-title', '') ?? '',
                'slug' => $category->slug . '-developer-centric',
                'type' => 'developer-centric',
                'body' => $request->input('developer-centric-body', '') ?? '',
                'published_at' => $now
            ],
            [
                'title' => $request->input('bundles-title', '') ?? '',
                'slug' => $category->slug . '-bundles',
                'type' => 'bundles',
                'body' => $request->input('bundles-body', '') ?? '',
                'published_at' => $now
            ],
            [
                'title' => $request->input('products-title', '') ?? '',
                'slug' => $category->slug . '-products',
                'type' => 'products',
                'body' => $request->input('products-body', '') ?? '',
                'published_at' => $now
            ],
        ]);

        return redirect()->route('admin.category.index')->with('alert', 'success:The category has been created.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.category.index')->with('alert', 'success:The category has been deleted.');
    }
}
