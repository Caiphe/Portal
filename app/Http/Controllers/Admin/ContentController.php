<?php

namespace App\Http\Controllers\Admin;

use App\Content;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DocsRequest;
use App\Http\Requests\Admin\PageRequest;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    // Admininster page content type
    public function indexPage(Request $request)
    {
        $sort = '';
        $order = $request->get('order', 'desc');
        $numberPerPage = (int)$request->get('number_per_page', '15');
        $pages = Content::whereType('page')
            ->when($request->has('q'), function ($q) use ($request) {
                $query = "%" . $request->q . "%";
                $q->where(function ($q) use ($query) {
                    $q->where('title', 'like', $query)
                        ->orWhere('body', 'like', $query);
                });
            });

        if ($request->has('sort')) {
            $sort = $request->get('sort');
            $pages->orderBy($sort, $order);
            $order = ['asc' => 'desc', 'desc' => 'asc'][$order] ?? 'desc';
        }

        if ($request->ajax()) {
            return response()
                ->view('components.admin.list', [
                    'collection' => $pages->paginate($numberPerPage),
                    'order' => $order,
                    'fields' => ['Title' => 'title', 'Published' => 'published_at|date:d M Y|addClass:not-on-mobile'],
                    'modelName' => 'page'
                ], 200)
                ->header('Vary', 'X-Requested-With')
                ->header('Content-Type', 'text/html');
        }

        return view('templates.admin.pages.index', [
            'pages' => $pages->paginate($numberPerPage),
            'order' => $order,
        ]);
    }

    public function editPage(Content $content)
    {
        return view('templates.admin.pages.edit', [
            'page' => $content
        ]);
    }

    public function updatePage(Content $content, PageRequest $request)
    {
        $content->update($request->only(['title', 'body']));

        return redirect()->route('admin.page.index')->with('alert', 'success:The content has been updated.');
    }

    public function createPage()
    {
        return view('templates.admin.pages.create');
    }

    public function storePage(PageRequest $request)
    {
        Content::create([
            'title' => $request->title,
            'body' => $request->body,
            'type' => 'page',
            'published_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->route('admin.page.index')->with('alert', 'success:The content has been updated.');
    }

    public function destroyPage(Content $content)
    {
        $content->delete();

        return redirect()->route('admin.page.index')->with('alert', 'success:The page has been deleted.');
    }

    // Administer documentation content type
    public function indexDoc(Request $request)
    {
        $sort = '';
        $order = $request->get('order', 'desc');
        $numberPerPage = (int)$request->get('number_per_page', '15');
        $docs = Content::whereType('general_docs')
            ->when($request->has('q'), function ($q) use ($request) {
                $query = "%" . $request->q . "%";
                $q->where(function ($q) use ($query) {
                    $q->where('title', 'like', $query)
                        ->orWhere('body', 'like', $query);
                });
            });


        if ($request->has('sort')) {
            $sort = $request->get('sort');
            $docs->orderBy($sort, $order);
            $order = ['asc' => 'desc', 'desc' => 'asc'][$order] ?? 'desc';
        }

        if ($request->ajax()) {
            return response()
                ->view('components.admin.list', [
                    'collection' => $docs->paginate($numberPerPage),
                    'order' => $order,
                    'fields' => ['Title' => 'title', 'Published' => 'published_at|date:d M Y|addClass:not-on-mobile'],
                    'modelName' => 'doc'
                ], 200)
                ->header('Vary', 'X-Requested-With')
                ->header('Content-Type', 'text/html');
        }

        return view('templates.admin.docs.index', [
            'docs' => $docs->paginate($numberPerPage),
            'order' => $order,
        ]);
    }

    public function editDoc(Content $content)
    {
        return view('templates.admin.docs.edit', [
            'doc' => $content
        ]);
    }

    public function updateDoc(Content $content, DocsRequest $request)
    {
        $content->update($request->only(['title', 'body']));

        return redirect()->route('admin.doc.index')->with('alert', 'success:The content has been created.');
    }

    public function createDoc()
    {
        return view('templates.admin.docs.create');
    }

    public function storeDoc(DocsRequest $request)
    {
        Content::create([
            'title' => $request->title,
            'body' => $request->body,
            'type' => 'general_docs',
            'published_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->route('admin.doc.index')->with('alert', 'success:The content has been created.');
    }

    public function destroyDoc(Content $content)
    {
        $content->delete();

        return redirect()->route('admin.doc.index')->with('alert', 'success:The documentation has been deleted.');
    }
}
