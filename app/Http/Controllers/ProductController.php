<?php

namespace App\Http\Controllers;

use App\Category;
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
		$user = $request->user();
		$assignedProducts = $user ? $user->assignedProducts()->with('category')->get() : [];
		$products = Product::with(['category', 'countries'])
			->where('category_cid', '!=', 'misc')
			->basedOnUser($request->user())
			->get()
			->merge($assignedProducts);
		$productCategories = $products->pluck('category.title', 'category.slug');
		$productsCollection = $products->sortBy('display_name')->groupBy('category.title')->sortKeys();
		$productLocations = $products->pluck('locations')->implode(',');
		$locations = array_unique(explode(',', $productLocations));
		$countries = Country::whereIn('code', $locations)->pluck('name', 'code');
		$content = Content::where('contentable_type', 'Products')->get();
		$hasPrivateProduct = $products->contains('access', 'private');
		$hasInternalProduct = $products->contains('access', 'internal');
		$productGroups = $products->pluck('group')->unique()->toArray();

		$categoryWithCount = Category::withCount('products')->get();
		// dd($categoryWithCount->toArray());

		return view('templates.products.index', [
			'productsCollection' => $productsCollection,
			'productCategories' => $productCategories,
			'countries' => $countries,
			'selectedCategory' => $request['category'],
			'content' => $content,
			'hasPrivateProduct' => $hasPrivateProduct,
			'hasInternalProduct' => $hasInternalProduct,
			'products' => $products,
			'productGroups' => $productGroups
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
		$user = $request->user();

		if ($user) {
			$user->load('assignedProducts');
			$assignedProducts = $user->assignedProducts->pluck('pid');
			abort_if($product->access === 'private' && (!$user || (!$assignedProducts->contains($product->pid) && !$user->hasRole('private'))), 403);
		} else {
			abort_if($product->access === 'private', 403);
		}


		$product->load(['content', 'keyFeatures', 'category', 'countries']);

		$content = [
			'all' => [],
			'lhs' => [],
			'rhs' => [],
		];
		$alternatives = [];
		$startingPoint = "product-specification";

		foreach ($product->content as $c) {
			$key = in_array($c->title, ['Overview', 'Docs']) ? 'lhs' : 'rhs';
			$content['all'][$c->title] = $c;
			$content[$key][$c->title] = $c;
		}

		// if (isset($content['lhs']['Overview'])) {
		// 	$startingPoint = 'product-' . $content['lhs']['Overview']->slug;
		// } else if (isset($content['lhs']['Docs'])) {
		// 	$startingPoint = 'product-' . $content['lhs']['Docs']->slug;
		// }

		if (!empty($alternatives[$product->category->title]) && count($alternatives[$product->category->title]) > 1) {
			$alternatives = array_intersect_key(
				$alternatives[$product->category->title],
				array_flip(
					array_rand(
						$alternatives[$product->category->title],
						min(count($alternatives[$product->category->title]), 3)
					)
				)
			);
		} else {
			$alternatives = [];
		}

		if ($product->swagger === null || !\Storage::disk('app')->exists("openapi/{$product->swagger}")) {
			return view('templates.products.show', [
				"product" => $product,
				"content" => $content,
				"startingPoint" => $startingPoint,
				"specification" => false,
				"alternatives" => $alternatives,
			]);
		}

		return view('templates.products.show', [
			"product" => $product,
			"content" => $content,
			"startingPoint" => $startingPoint,
			"specification" => true,
			"alternatives" => $alternatives,
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
