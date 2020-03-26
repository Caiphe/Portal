<?php

namespace App\Http\Controllers;

use App\Product;
use App\Services\OpenApiService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $openApiClass = new OpenApiService($product->swagger);
        $prod = $product->load(['content', 'keyFeatures']);
        $productList = Product::isPublic()->get()->groupBy('category');
        $content = [];
        $sidebarAccordion = [];
        $startingPoint = "product-specification";

        foreach ($prod->content as $c) {
            $content[$c->type] = $c;
        }

        if(isset($content['product_overview'])){
            $startingPoint = 'product-overview';
        } else if(isset($content['product_docs'])){
            $startingPoint = 'product-docs';
        }

        foreach ($productList as $category => $products) {
            if(!isset($sidebarAccordion[$category])){
                $sidebarAccordion[$category] = [];
            }

            foreach ($products as $key => $product) {
                $sidebarAccordion[$category][] = ["label" => $product['display_name'], "link" => '/products/' . $product['slug']];
            }
        }

        return view('products.show', [
            "product" => $prod,
            "sidebarAccordion" => $sidebarAccordion,
            "content" => $content,
            "startingPoint" => $startingPoint,
            "specification" => $openApiClass->buildOpenApiJson()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    public function downloadPostman(Product $product)
    {
        $openApiClass = new OpenApiService($product->swagger);
        $openApi = $openApiClass->buildOpenApiJson();

        return response()->streamDownload(function () use ($product, $openApi) {
            echo json_encode($openApi, JSON_PRETTY_PRINT);
        }, $product->slug . '.json');
    }

    public function downloadSwagger(Product $product)
    {
        return response()->download(public_path() . '/openapi/' . $product->swagger);
    }
}
