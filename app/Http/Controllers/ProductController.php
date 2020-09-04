<?php

namespace App\Http\Controllers;

use App\Content;
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
		$products = Product::with('category')->where('category_cid', '!=', 'misc')->basedOnUser($request->user())->get();
		$productsCollection = $products->sortBy('category.title')->groupBy('category.title');
		$productLocations = $products->pluck('locations')->implode(',');
		$locations = array_unique(explode(',', $productLocations));
		$countries = Country::whereIn('code', $locations)->pluck('name', 'code');
		$content = Content::where('contentable_type', 'Products')->get();

		return view('templates.products.index', [
			'productsCollection' => $productsCollection,
			'productCategories' => array_keys($productsCollection->toArray()),
			'countries' => $countries,
			'selectedCategory' => $request['category'],
			'groups' => $products->pluck('group')->unique(),
			'content' => $content
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
		$content = [
			'all' => [],
			'lhs' => [],
			'rhs' => [],
		];
		$sidebarAccordion = [];
		$startingPoint = "product-specification";

		foreach ($product->content as $c) {
			$key = in_array($c->title, ['Overview', 'Docs']) ? 'lhs' : 'rhs';
			$content['all'][$c->title] = $c;
			$content[$key][$c->title] = $c;
		}

		if (isset($content['lhs']['Overview'])) {
			$startingPoint = 'product-' . $content['lhs']['Overview']->slug;
		} else if (isset($content['lhs']['Docs'])) {
			$startingPoint = 'product-' . $content['lhs']['Docs']->slug;
		}

		foreach ($productList as $category => $products) {
			if (!isset($sidebarAccordion[$category])) {
				$sidebarAccordion[$category] = [];
			}

			foreach ($products as $sidebarProduct) {
				$sidebarAccordion[$category][] = ["label" => $sidebarProduct['display_name'], "link" => '/products/' . $sidebarProduct['slug']];
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
