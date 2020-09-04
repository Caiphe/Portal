<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function openapiUpload(Product $product, Request $request)
    {
        $filename = $product->slug . '.yaml';
        $path = $request->file('openApi')->storeAs('openapi', $filename, 'app');
        $product->update(['swagger' => $filename]);

        return response()->json([
            'success' => true,
            'body' => $path
        ]);
    }

    public function imageUpload(Product $product, Request $request)
    {
        $path = $request->file('file')->storeAs('editor', $request->key, 'app');

        return response()->json([
            'success' => true,
            'body' => '/' . $path
        ], 201);
    }
}
