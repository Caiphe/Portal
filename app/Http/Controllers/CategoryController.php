<?php

namespace App\Http\Controllers;

use App\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $category->load(['products', 'bundles']);

        return view(
            'templates.category.show',
            [
                'theme' => $category->theme,
                'category' => $category->title,
                'slug' => $category->slug,
            ]
        );
    }
}
