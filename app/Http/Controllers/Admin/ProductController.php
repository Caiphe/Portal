<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')->basedOnUser($request->user());

        if ($request->has('q')) {
            $query = "%" . $request->q . "%";
            $products->where(function ($q) use ($query) {
                $q->where('display_name', 'like', $query)
                    ->orWhereHas('content', function ($q) use ($query) {
                        $q->where('title', 'like', $query)
                            ->orWhere('body', 'like', $query);
                    });
            });
        }

        if ($request->ajax()) {
            return response()
                ->view('components.admin.table-data', [
                    'collection' => $products->orderBy('display_name')->paginate(),
                    'fields' => ['display_name', 'access', 'environments', 'category.title'],
                    'modelName' => 'product'
                ], 200)
                ->header('Content-Type', 'text/html');
        }

        return view('templates.admin.home', [
            'products' => $products->orderBy('display_name')->paginate()
        ]);
    }

    public function edit(Product $product)
    {
        $product->load('content');

        return view('templates.admin.products.edit', [
            'product' => $product,
            'content' => $product->content->groupBy('title')
        ]);
    }

    public function update(Product $product, Request $request)
    {
        $now = date('Y-m-d H:i:s');
        $contents = [];
        $tabs = $request->get('tab', []);

        for ($i = 0; $i < count($tabs['title']); $i++) {
            if ($tabs['title'][$i] === null || $tabs['body'][$i] === null) continue;

            $contents[] = [
                'title' => $tabs['title'][$i],
                'body' => $tabs['body'][$i],
                'slug' => $product->slug . '-' . Str::slug($tabs['title'][$i]),
                'type' => 'tab',
                'published_at' => $now,
            ];
        }

        $product->content()->delete();
        $product->content()->createMany($contents);

        return redirect()->route('admin.product.index')->with('alert', 'success:The content has been updated.');
    }
}
