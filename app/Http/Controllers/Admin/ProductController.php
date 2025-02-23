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
use Illuminate\Database\Eloquent\Collection;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $access = $request->get('access', "");
        $group = $request->get('group', "");
        $category = $request->get('category', "");
        $numberPerPage = (int)$request->get('number_per_page', '15');
        $category = Str::slug($category);

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
            })
            ->when($category !== "" && !is_null($category), function ($q) use ($category) {
                $q->where('category_cid', $category);
            })
            ->when($group !== "" && !is_null($group), function ($q) use ($request) {
                $q->where('group', $request->get('group'));
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

        $productGroup = Product::pluck('group')->unique()->toArray();
        $productCat = Product::pluck('category_cid')->unique()->toArray();
        $productCategory = Category::whereIn('slug', $productCat)->pluck('title')->unique()->toArray();
        
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
            'productGroup' => $productGroup,
            'productCategory' => $productCategory,
            'productsCount' => Product::count(),
        ]);
    }

    public function edit(Product $product)
    {
        $product->load('content', 'countries');
        $hasSwagger = $product->swagger !== null && \Storage::disk('app')->exists("openapi/{$product->swagger}");
        $productLocations = Country::whereIn('code', explode(',', $product->locations))->pluck('name')->toArray();

        return view('templates.admin.products.edit', [
            'product' => $product,
            'content' => $product->content->groupBy('title'),
            "countries" => $productLocations,
            'categories' => Category::pluck('title', 'cid'),
            'hasSwagger' => $hasSwagger,
            'logs' => $product->logs()->latest()->get(),
        ]);
    }

    public function update(Product $product, ProductRequest $request)
    {
        $data = $request->validated();
        $now = date('Y-m-d H:i:s');
        $contents = [];
        $tabs = $data['tab'] ?? [];
        $updatedFields = [];
        $logs = [];

        $product->update([
            'category_cid' => $request['category_cid'],
            'description' => $data['description'],
        ]);

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

        $contentdData = $this->compareContent($contents, $product->content->toArray());
        $updatedFields = array_keys($product->getChanges());
        unset($updatedFields['updated_at']);

        if(!empty($updatedFields) || !empty($contentdData)){
            $messages = '';

            for($i = 0; $i < count($updatedFields); $i++){
                if($updatedFields[$i] === 'category_cid') $messages .= 'Category updated ' .'<br/>';
                if($updatedFields[$i] === 'description')  $messages .= 'Description updated ' .'<br />';
            }

            $messages.= $contentdData;

           $logs[]= [
                'user_id' => auth()->user()->id,
                'message' => $messages,
            ];
        }

        $product->content()->delete();
        $product->content()->createMany($contents);
        $product->logs()->createMany($logs);
        return redirect()->route('admin.product.index')->with('alert', 'success:The content has been updated.');
    }

    protected function compareContent(array $currentContent, array $updatedContent): string
    {
        $currentColumn = array_column($currentContent, 'title');
        $updatedColumn = array_column($updatedContent, 'title');
        $currentContent = array_combine($currentColumn, $currentContent);
        $updatedContent = array_combine($updatedColumn, $updatedContent);

        $updated = '';

        $addedColumn = array_diff($currentColumn, $updatedColumn);
        $removedColumn = array_diff($updatedColumn, $currentColumn);

        foreach($addedColumn as $key=>$column){
            if(isset($currentContent[$column]['body']) && empty($currentContent[$column]['body'])){
                unset($addedColumn[$key]);
            }
        }

        if(!empty($addedColumn)){
            $updated .= implode(', ', $addedColumn) .' was added <br />';
        }

        if(!empty($removedColumn)){
            $updated .= implode(', ', $removedColumn) .' was removed <br />';
        }

        $sameColumns = array_intersect($currentColumn, $updatedColumn);

        if(!empty($sameColumns)){
            foreach($sameColumns as $title){
                if($currentContent[$title]['body'] !== $updatedContent[$title]['body']){
                    $updated .= "{$title} was updated <br />";
                }
            }
        }

        return $updated;
    }
}
