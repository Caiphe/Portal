<?php

namespace App\Http\Controllers;

use App\Category;

class CategoryController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param      \App\Category              $category
     *
     * @return     \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $category->load(['products', 'bundles', 'content']);
        $products = $category->products->shuffle()->take(3)->map(function ($product) {
            return [
                'name' => $product['name'],
                'group' => $product['group'],
                'description' => $product['description'] ?: 'View the product',
                'locations' => $product['locations'] === 'all' ? ['All' => 'all'] : $product->countries()->pluck('code', 'name')->toArray(),
                'href' => route('product.show', $product['slug']),
            ];
        })->pad(-3, [
            'name' => '',
            'group' => '',
            'description' => '',
            'locations' => [],
            'href' => '',
        ])->toArray();

        return view(
            'templates.category.show',
            [
                'theme' => $category->theme,
                'category' => $category->title,
                'slug' => $category->slug,
                'content' => $category->content->groupBy('type'),
                'products' => $products,
                'bundles' => [],
            ]
        );
    }
}
