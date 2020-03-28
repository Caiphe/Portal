<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Content;

class GettingStartedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contents = Content::where('type', 'general_docs')->get();
        $contentList = [];
        
        foreach($contents as $content){
            $contentList[] = ['label' => $content['title'], 'link' => '/getting-started/' . $content['slug']];
        }

        $list =  [
                [ 'label' => 'Introduction', 'link' => '/getting-started'],
                [ 'label' => 'My apps', 'link' => '/apps'],
                [ 'label' => 'Browse products','link' => '/products'],
                [ 'label' => 'Create an application','link' => '/apps/create'],
                [ 'label' => 'FAQ','link' => '/faq'],
        ];
        $list = [ 'GETTING STARTED' => array_merge($list, $contentList)];

        return view('templates.getting-started.index', [
            "content"=> $contents,
            "list"=> $list
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Content $content)
    {
        $contents = Content::where('type', 'general_docs')->get();
        $contentList = [];
        
        foreach($contents as $conten){
            $contentList[] = ['label' => $conten['title'], 'link' => '/getting-started/' . $conten['slug']];
        }

        $list =  [
                [ 'label' => 'Introduction', 'link' => '/getting-started'],
                [ 'label' => 'My apps', 'link' => '/apps'],
                [ 'label' => 'Browse products','link' => '/products'],
                [ 'label' => 'Create an application','link' => '/apps/create'],
                [ 'label' => 'FAQ','link' => '/faq'],
        ];
        
        $list = [ 'GETTING STARTED' => array_merge($list, $contentList)];

        return view('templates.getting-started.show', [
            "content"=> $content,
            "list"=> $list
        ]);

        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
