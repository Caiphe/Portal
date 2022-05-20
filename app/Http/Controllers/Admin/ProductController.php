<?php

namespace App\Http\Controllers\Admin;

use App\Log;
use App\Country;
use App\Product;
use App\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $access = $request->get('access', "");
        $numberPerPage = (int)$request->get('number_per_page', '15');
        $products = Product::with('category')
            ->select([
                'products.*',
                'categories.title AS category_title'
            ])->join('categories', 'products.category_cid', '=', 'categories.cid')
            ->byResponsibleCountry($request->user())
            ->when($request->has('q'), function ($q) use ($request) {
                $query = "%" . $request->get('q') . "%";

                $q->where(function ($q) use ($query) {
                    $q->where('display_name', 'like', $query)
                        ->orWhereHas('content', function ($q) use ($query) {
                            $q->where('title', 'like', $query)
                                ->orWhere('body', 'like', $query);
                        });
                });
            })
            ->when($access !== "" && !is_null($access), function ($q) use ($request) {
                $q->where('access', $request->get('access'));
            });
        $order = $request->get('order', 'desc');
        $sort = '';

        if ($request->has('sort')) {
            $sort = $request->get('sort');

            if ($sort === 'category.title') {
                $products->orderBy('category_title', $order);
            } else {
                $products->orderBy($sort, $order);
            }

            $order = ['asc' => 'desc', 'desc' => 'asc'][$order] ?? 'desc';
        }

        if ($request->ajax()) {
            return response()
                ->view('components.admin.list', [
                    'collection' => $products->orderBy('display_name')->paginate($numberPerPage),
                    'order' => $order,
                    'fields' => ['Name' => 'display_name', 'Access level' => 'access|addClass:not-on-mobile', 'Environments' => 'environments|splitToTag:,|addClass:not-on-mobile', 'Category' => 'category.title|addClass:not-on-mobile'],
                    'modelName' => 'product'
                ], 200)
                ->header('Vary', 'X-Requested-With')
                ->header('Content-Type', 'text/html');
        }

        return view('templates.admin.products.index', [
            'products' => $products->orderBy('display_name')->paginate($numberPerPage),
            'order' => $order,
        ]);
    }

    public function edit(Product $product)
    {
        $product->load('content', 'countries');
        $hasSwagger = $product->swagger !== null && \Storage::disk('app')->exists("openapi/{$product->swagger}");

        return view('templates.admin.products.edit', [
            'product' => $product,
            'content' => $product->content->groupBy('title'),
            'countries' => Country::get(),
            'categories' => Category::pluck('title', 'cid'),
            'hasSwagger' => $hasSwagger,
            'logs' => Log::all(),
        ]);
    }

    public function update(Product $product, ProductRequest $request)
    {
        $data = $request->validated();
        $now = date('Y-m-d H:i:s');
        $contents = [];
        $tabs = $data['tab'] ?? [];

        // dd($data);

        $product->update([
            'display_name' => $data['display_name'],
            'locations' =>  implode(',', $data['locations']),
            'group' => $data['group'] ?? 'MTN',
            'category_cid' => $request['category_cid'],
        ]);

        $product->countries()->sync($request->get('locations', Country::all()));

        for ($i = 0; $i < count($tabs['title']); $i++) {
            if ($tabs['title'][$i] === null || $tabs['body'][$i] === null) continue;

            $contents[] = [
                'title' => $tabs['title'][$i],
                'body' => $tabs['body'][$i],
                'slug' => $product->slug . '-' . Str::slug($tabs['title'][$i]),
                'type' => 'tab',
                'published_at' => $now,
            ];
        }

        $product->content()->delete();
        $product->content()->createMany($contents);

        return redirect()->route('admin.product.index')->with('alert', 'success:The content has been updated.');
    }
}
