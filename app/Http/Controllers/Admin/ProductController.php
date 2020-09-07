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
        return view('templates.admin.home', [
            'products' => Product::with('category')->basedOnUser($request->user())->orderBy('display_name')->paginate()
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

        foreach ($request->get('content', []) as $slug => $content) {
            if ($content['body'] === null) continue;
            $contents[] = [
                'title' => $content['title'],
                'body' => $content['body'],
                'slug' => $product->slug . '-' . Str::slug($content['title']),
                'type' => 'tab',
                'published_at' => $now,
            ];
        }

        if ($request->has('tab')) {
            $tabs = $request->get('tab', []);
            for ($i = 0; $i < count($tabs['title']); $i++) {
                if($tabs['title'][$i] === null || $tabs['body'][$i] === null) continue;

                $contents[] = [
                    'title' => $tabs['title'][$i],
                    'body' => $tabs['body'][$i],
                    'slug' => $product->slug . '-' . Str::slug($tabs['title'][$i]),
                    'type' => 'tab',
                    'published_at' => $now,
                ];
            }
        }

        $product->content()->delete();
        $product->content()->createMany($contents);

        return redirect()->back()->with('alert', 'success:The content has been updated.');
    }
}
