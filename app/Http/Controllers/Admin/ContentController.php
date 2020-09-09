<?php

namespace App\Http\Controllers\Admin;

use App\Content;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    // Admininster page content type

    public function indexPage(Request $request)
    {
        $pages = Content::whereType('page');

        if ($request->has('q')) {
            $query = "%" . $request->q . "%";
            $pages->where(function ($q) use ($query) {
                $q->where('title', 'like', $query)
                    ->orWhere('body', 'like', $query);
            });
        }

        return view('templates.admin.pages.index', [
            'pages' => $pages->paginate()
        ]);
    }

    public function editPage(Content $content)
    {
        return view('templates.admin.pages.edit', [
            'page' => $content
        ]);
    }

    public function updatePage(Content $content, Request $request)
    {
        $content->update($request->only(['title', 'body']));
        $content->fresh();

        return redirect()->route('admin.page.edit', $content->slug)->with('alert', 'success:The content has been updated.');
    }

    public function createPage()
    {
        return view('templates.admin.pages.create');
    }

    public function storePage(Request $request)
    {
        $content = Content::create([
            'title' => $request->title,
            'body' => $request->body,
            'type' => 'page',
            'published_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->route('admin.page.edit', $content->slug)->with('alert', 'success:The content has been updated.');
    }

    public function destroyPage(Content $content)
    {
        $content->delete();

        return redirect()->route('admin.page.index')->with('alert', 'success:The page has been deleted.');
    }

    // Administer documentation content type
    public function indexDoc(Request $request)
    {
        $docs = Content::whereType('general_docs');

        if ($request->has('q')) {
            $query = "%" . $request->q . "%";
            $docs->where(function ($q) use ($query) {
                $q->where('title', 'like', $query)
                    ->orWhere('body', 'like', $query);
            });
        }

        return view('templates.admin.docs.index', [
            'docs' => $docs->paginate()
        ]);
    }

    public function editDoc(Content $content)
    {
        return view('templates.admin.docs.edit', [
            'doc' => $content
        ]);
    }

    public function updateDoc(Content $content, Request $request)
    {
        $content->update($request->only(['title', 'body']));
        $content->fresh();

        return redirect()->route('admin.doc.edit', $content->slug)->with('alert', 'success:The content has been created.');
    }

    public function createDoc()
    {
        return view('templates.admin.docs.create');
    }

    public function storeDoc(Request $request)
    {
        $content = Content::create([
            'title' => $request->title,
            'body' => $request->body,
            'type' => 'general_docs',
            'published_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->route('admin.doc.edit', $content->slug)->with('alert', 'success:The content has been created.');
    }

    public function destroyDoc(Content $content)
    {
        $content->delete();

        return redirect()->route('admin.doc.index')->with('alert', 'success:The documentation has been deleted.');
    }
}
