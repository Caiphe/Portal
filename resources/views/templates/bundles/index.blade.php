@extends('layouts.sidebar')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/bundles/index.css') }}">
@endpush

@section('title', 'Bundles')

@section('sidebar')
<div class="filter-sidebar">
    <input type="text" name="filter-text" id="filter-text" class="filter-text" placeholder="Search" autofocus/>
    
    <h3>Categories</h3>
    @foreach ($categories as $id => $category)
    <div class="filter-checkboxs">
        <input class="filter-checkbox" type="checkbox" data-name="{{ $id }}" value="{{ $id }}" id="{{ $id }}" autocomplete="off" />
        <label class="filter-label" for="{{ $id }}">{{ $category}}</label>
    </div>
    @endforeach

    <button id="clearFilter" class="dark outline"
        @isset($selectedCategory)
            style="display:block"
        @endisset>
        Clear filters
    </button>
</div>
@endsection

@section('banner')
<div id="banner"></div>
@endsection

@section('content')
    <div class="header-block">
        <h1>Bundles</h1>
        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
        @svg('people', null, 'images/illustrations')
    </div>
    {{-- <div class="content">
        @foreach($bundles as $bundle)
        <x-card-link
            :id="'bundle-' . $bundle->slug"
            class="bundle-card"
            :title="$bundle->display_name" 
            :href="route('bundle.show', $bundle->slug)"
            :data-title="$bundle->display_name"
            :data-name="$bundle->name"
            :data-description="$bundle->description ?? ''"
            :data-category="$bundle->category->cid ?? ''"
        >
            @subStr(($bundle->description ?: 'View the bundle'))
        </x-card-link>
        @endforeach
    </div> --}}
@endsection

@push('scripts')
<script src="{{ mix('/js/templates/bundles/index.js') }}" defer></script>
@endpush