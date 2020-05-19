@extends('layouts.sidebar')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/bundles/index.css') }}">
@endpush

@section('title', 'Bundles')

@section('sidebar')
<div class="filter-sidebar">
    <h3>Bundles</h3>
    @foreach ($bundles as $bundle)
    <div class="filter-checkboxs">
        <input class="filter-checkbox" type="checkbox" data-name="{{$bundle->slug}}" value="{{$bundle->display_name}}" checked id="{{$bundle->slug}}"/>
        <label class="filter-label" for="{{$bundle->slug}}">{{$bundle->display_name}}</label>
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

@section('content')
    <x-heading heading="Bundles">
        <input type="text" name="filter-text" id="filter-text" class="filter-text" placeholder="Search" autofocus/>
    </x-heading>

    <div class="content">
        @foreach($bundles as $bundle)
        <x-card-link
            :id="'bundle-' . $bundle->slug"
            class="bundle-card"
            :title="$bundle->display_name" 
            :href="route('bundle.show', $bundle->slug)"
            :data-title="$bundle->display_name"
            :data-name="$bundle->name"
            :data-description="$bundle->description ?? ''"
        >
            @subStr(($bundle->description ?: 'View the bundle'))
        </x-card-link>
        @endforeach
    </div>
@endsection

@push('scripts')
<script src="{{ mix('/js/templates/bundles/index.js') }}" defer></script>
@endpush