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
        $bundles = Bundle::with('category')->byResponsibleCountry($request->user());

        if ($request->has('q')) {
            $query = "%" . $request->q . "%";
            $bundles->where(function ($q) use ($query) {
                $q->where('display_name', 'like', $query)
                    ->orWhereHas('content', function ($q) use ($query) {
                        $q->where('title', 'like', $query)
                            ->orWhere('body', 'like', $query);
                    });
            });
        }

        if ($request->ajax()) {
            return response()
                ->view('components.admin.table-data', [
                    'collection' => $bundles->orderBy('display_name')->paginate(),
                    'fields' => ['display_name', 'category.title'],
                    'modelName' => 'bundle'
                ], 200)
                ->header('Vary', 'X-Requested-With')
                ->header('Content-Type', 'text/html');
        }

        return view('templates.admin.bundles.index', [
            'bundles' => $bundles->orderBy('display_name')->paginate()
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
