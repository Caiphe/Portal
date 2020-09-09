@extends('layouts.sidebar')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/getting-started/show.css') }}">
@endpush

@section('title', $content['title'])

@section('sidebar')
    <x-sidebar-accordion id="sidebar-accordion" :active="'/' . request()->path()"
    :list="$list" />
@endsection

@section("content")
    <x-heading :heading="$content['title']" tags="Working with our products">
    </x-heading>
    <div class="content-body">
        {!! $content['body'] !!}
    </div>
@endsection
