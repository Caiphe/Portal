<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class ProductController extends Controller
{
    public function openapiUpload(Product $product, Request $request)
    {
        try {
            Yaml::parse($request->file('openApi')->get());
        } catch (ParseException $exception) {
            return response()->json([
                'success' => false,
                'message' => "Openapi Spec isn't valid"
            ], 400);
        }
        $filename = $product->slug . '.yaml';
        $path = $request->file('openApi')->storeAs('openapi', $filename, 'app');
        $product->update(['swagger' => $filename]);

        Cache::forget($product->slug . '-specification');

        return response()->json([
            'success' => true,
            'body' => $path,
            'message' => "Successfully uploaded OpenApi spec"
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
