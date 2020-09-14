<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query();

        if ($request->has('q')) {
            $query = "%" . $request->q . "%";
            $categories->where(function ($q) use ($query) {
                $q->where('title', 'like', $query)
                    ->orWhereHas('content', function ($q) use ($query) {
                        $q->where('title', 'like', $query)
                            ->orWhere('body', 'like', $query);
                    });
            });
        }

        if ($request->ajax()) {
            return response()
                ->view('components.admin.table-data', [
                    'collection' => $categories->paginate(),
                    'fields' => ['title', 'theme'],
                    'modelName' => 'category'
                ], 200)
                ->header('Content-Type', 'text/html');
        }

        return view('templates.admin.categories.index', [
            'categories' => $categories->paginate()
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

    public function update(Category $category, Request $request)
    {
        $now = date('Y-m-d H:i:s');
        $categoryData = $request->only(['title', 'theme']);
        $categoryData['description'] = $request->input('heading-title', '');
        $updateRelationships = $categoryData['title'] !== $category->title;
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
                'title' => $request->input('heading-title', ''),
                'slug' => $category->slug . '-heading',
                'type' => 'heading',
                'body' => $request->input('heading-body', ''),
                'published_at' => $now
            ],
            [
                'title' => $request->input('benefits-title', ''),
                'slug' => $category->slug . '-benefits',
                'type' => 'benefits',
                'body' => $request->input('benefits-body', ''),
                'published_at' => $now
            ],
            [
                'title' => $request->input('developer-centric-title', ''),
                'slug' => $category->slug . '-developer-centric',
                'type' => 'developer-centric',
                'body' => $request->input('developer-centric-body', ''),
                'published_at' => $now
            ],
            [
                'title' => $request->input('bundles-title', ''),
                'slug' => $category->slug . '-bundles',
                'type' => 'bundles',
                'body' => $request->input('bundles-body', ''),
                'published_at' => $now
            ],
            [
                'title' => $request->input('products-title', ''),
                'slug' => $category->slug . '-products',
                'type' => 'products',
                'body' => $request->input('products-body', ''),
                'published_at' => $now
            ],
        ]);

        $category->fresh();

        if ($updateRelationships) {
            foreach ($relationships as $type => $collection) {
                $collection->each(fn ($faq) => $faq->update(['category_cid' => $category->cid]));
            }
        }

        return redirect()->route('admin.category.edit', $category->slug)->with('alert', 'success:The category has been updated.');
    }

    public function create()
    {
        return view('templates.admin.categories.create');
    }

    public function store(Request $request)
    {
        $now = date('Y-m-d H:i:s');
        $categoryData = $request->only(['title', 'theme']);
        $categoryData['description'] = $request->input('heading-title', '');

        $category = Category::create($categoryData);
        $category->content()->createMany([
            [
                'title' => $request->input('heading-title', ''),
                'slug' => $category->slug . '-heading',
                'type' => 'heading',
                'body' => $request->input('heading-body', ''),
                'published_at' => $now
            ],
            [
                'title' => $request->input('benefits-title', ''),
                'slug' => $category->slug . '-benefits',
                'type' => 'benefits',
                'body' => $request->input('benefits-body', ''),
                'published_at' => $now
            ],
            [
                'title' => $request->input('developer-centric-title', ''),
                'slug' => $category->slug . '-developer-centric',
                'type' => 'developer-centric',
                'body' => $request->input('developer-centric-body', ''),
                'published_at' => $now
            ],
            [
                'title' => $request->input('bundles-title', ''),
                'slug' => $category->slug . '-bundles',
                'type' => 'bundles',
                'body' => $request->input('bundles-body', ''),
                'published_at' => $now
            ],
            [
                'title' => $request->input('products-title', ''),
                'slug' => $category->slug . '-products',
                'type' => 'products',
                'body' => $request->input('products-body', ''),
                'published_at' => $now
            ],
        ]);

        return redirect()->route('admin.category.edit', $category->slug)->with('alert', 'success:The category has been created.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.category.index')->with('alert', 'success:The category has been deleted.');
    }
}
