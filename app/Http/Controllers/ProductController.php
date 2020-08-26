<?php

namespace App\Http\Controllers;

use App\Country;
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
	public function index(Request $request)
	{
		$products = Product::with('category')->basedOnUser($request->user())->get();
		$productsCollection = $products->sortBy('category.title')->groupBy('category.title');
		$productLocations = $products->pluck('locations')->implode(',');
		$locations = array_unique(explode(',', $productLocations));
		$countries = Country::whereIn('code', $locations)->pluck('name', 'code');

		return view('templates.products.index', [
			'productsCollection' => $productsCollection,
			'productCategories' => array_keys($productsCollection->toArray()),
			'countries' => $countries,
			'selectedCategory' => $request['category'],
			'groups' => $products->pluck('group')->unique()
		]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, Product $product)
	{
		$product->load(['content', 'keyFeatures']);

		if ($product->swagger === null || !\Storage::disk('app')->exists("openapi/{$product->swagger}")) {
			return redirect()->route('product.index');
		}

		$openApiClass = new OpenApiService($product->swagger);
		$productList = Product::with('category')->basedOnUser($request->user())->get()->sortBy('category.title')->groupBy('category.title');
		$content = ['tab' => []];
		$sidebarAccordion = [];
		$startingPoint = "product-specification";

		foreach ($product->content as $c) {
			if ($c->type === "tab") {
				$content[$c->type][] = $c;
				continue;
			}
			$content[$c->type] = $c;
		}

		if (isset($content['overview'])) {
			$startingPoint = 'overview';
		} else if (isset($content['docs'])) {
			$startingPoint = 'docs';
		}

		foreach ($productList as $category => $products) {
			if (!isset($sidebarAccordion[$category])) {
				$sidebarAccordion[$category] = [];
			}

			foreach ($products as $product) {
				$sidebarAccordion[$category][] = ["label" => $product['display_name'], "link" => '/products/' . $product['slug']];
			}

			asort($sidebarAccordion[$category]);
		}

		return view('templates.products.show', [
			"product" => $product,
			"sidebarAccordion" => $sidebarAccordion,
			"content" => $content,
			"startingPoint" => $startingPoint,
			"specification" => $openApiClass->buildOpenApiJson(),
		]);
	}

	public function downloadPostman(Product $product)
	{
		$openApiClass = new OpenApiService($product->swagger);
		$openApi = $openApiClass->buildOpenApiJson();

		return response()->streamDownload(function () use ($openApi) {
			echo json_encode($openApi, JSON_PRETTY_PRINT);
		}, $product->slug . '.json');
	}

	public function downloadSwagger(Product $product)
	{
		return response()->download(public_path() . '/openapi/' . $product->swagger);
	}
}
