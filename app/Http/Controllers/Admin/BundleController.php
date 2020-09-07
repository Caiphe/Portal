<?php

namespace App\Http\Controllers\Admin;

use App\Bundle;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    public function index(Request $request)
    {
        return view('templates.admin.bundles.index', [
            'bundles' => Bundle::with('category')->orderBy('display_name')->paginate()
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

    public function update(Bundle $bundle, Request $request)
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

        return redirect()->back()->with('alert', 'success:The content has been updated.');
    }
}
