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
        $list =  [ 'GETTING STARTED' => 
            [
                [ 'label' => 'Introduction', 'link' => '/getting-started'],
                [ 'label' => 'My apps', 'link' => '/my-apps'],
                [ 'label' => 'Browse products','link' => '/browse-products'],
                [ 'label' => 'Create an application','link' => '/create-aplication'],
                [ 'label' => 'Request approval','link' => '/request-approval'],
                [ 'label' => 'Responses and error codes','link' => '/responses'],
                [ 'label' => 'FAQ','link' => '/faq'],
                [ 'label' => 'Developer tips','link' => '/developer-tips']
            ]
        ];

        return view('templates.getting-started.index', [
            "content"=> Content::where('type', 'general_docs')->get(),
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
        return view('templates.getting-started.show');
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
