@extends('layouts.sidebar')

@section('title', $content['title'])

@section('sidebar')
    <x-sidebar-accordion id="sidebar-accordion" :active="'/' . request()->path()"  
    :list="$list" />  
@endsection

@section("content")
    <x-heading :heading="$content['title']" tags="Working with our products"  :edit="route('admin.doc.edit', $content->slug)">
    </x-heading>
    <div class="content-body">
        {!! $content['body'] !!}
    </div>
@endsection