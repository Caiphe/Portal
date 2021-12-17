<?php

namespace App\Http\Controllers\Admin;

use App\Bundle;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BundleRequest;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    public function index(Request $request)
    {
        $sort = '';
        $order = $request->get('order', 'desc');
        $bundles = Bundle::with('category')
            ->byResponsibleCountry($request->user())
            ->select([
                'bundles.*',
                'categories.title AS category_title'
            ])->join('categories', 'bundles.category_cid', '=', 'categories.cid')
            ->when($request->has('q'), function ($q) use ($request) {
                $query = "%" . $request->q . "%";
                $q->where(function ($q) use ($query) {
                    $q->where('display_name', 'like', $query)
                        ->orWhereHas('content', function ($q) use ($query) {
                            $q->where('title', 'like', $query)
                                ->orWhere('body', 'like', $query);
                        });
                });
            });

        if ($request->has('sort')) {
            $sort = $request->get('sort');

            if ($sort === 'category.title') {
                $bundles->orderBy('category_title', $order);
            } else {
                $bundles->orderBy($sort, $order);
            }

            $order = ['asc' => 'desc', 'desc' => 'asc'][$order] ?? 'desc';
        }

        if ($request->ajax()) {
            return response()
                ->view('components.admin.list', [
                    'collection' => $bundles->orderBy('display_name')->paginate(),
                    'order' => $order,
                    'fields' => ['display_name', 'category.title'],
                    'modelName' => 'bundle'
                ], 200)
                ->header('Vary', 'X-Requested-With')
                ->header('Content-Type', 'text/html');
        }

        return view('templates.admin.bundles.index', [
            'bundles' => $bundles->orderBy('display_name')->paginate(),
            'order' => $order,
        ]);
    }

    public function edit(Bundle $bundle)
    {
        $bundle->load('content');

        return view('templates.admin.bundles.edit', [
            'bundle' => $bundle,
            'content' => $bundle->content->groupBy('type')
        ]);
    }

    public function update(Bundle $bundle, BundleRequest $request)
    {
        $content = $bundle->content()->whereType('overview')->first();

        if ($content) {
            $content->update($request->only(['body']));
        } else {
            $bundle->content()->create([
                'title' => 'Overview',
                'body' => $request->get('body', ''),
                'type' => 'overview',
                'published_at' => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->route('admin.bundle.index')->with('alert', 'success:The content has been updated.');
    }
}
