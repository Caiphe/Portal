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

        return view(
            'templates.category.show',
            [
                'theme' => $category->theme,
                'category' => $category->title,
                'slug' => $category->slug,
                'content' => $category->content->groupBy('type'),
                'products' => $category->products->map(function ($product) {
                    return [
                        'name' => $product['name'],
                        'group' => $product['group'],
                        'description' => $product['description'] ?: 'View the product',
                        'locations' => explode(',', $product['locations'] ?? 'all'),
                        'href' => route('product.show', $product['slug']),
                    ];
                })->toArray(),
                'bundles' => $category->bundles->map(function ($bundle) {
                    return [
                        'name' => $bundle['name'],
                        'group' => $bundle['group'],
                        'description' => $bundle['description'] ?: 'View the bundle',
                        'locations' => explode(',', $bundle['locations'] ?? 'all'),
                        'href' => route('bundle.show', $bundle['slug']),
                    ];
                })->toArray(),
            ]
        );
    }
}
