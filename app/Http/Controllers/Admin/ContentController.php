<?php

namespace App\Http\Controllers\Admin;

use App\Content;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    // Admininster page content type

    public function indexPage()
    {
        return view('templates.admin.pages.index', [
            'pages' => Content::whereType('page')->paginate()
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

    // Administer documentation content type

    public function indexDoc()
    {
        return view('templates.admin.docs.index', [
            'docs' => Content::whereType('general_docs')->paginate()
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

        return redirect()->route('admin.doc.edit', $content->slug)->with('alert', 'success:The content has been updated.');
    }
}
