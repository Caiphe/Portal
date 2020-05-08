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
    <x-heading heading="Packages">
        <input type="text" name="filter-text" id="filter-text" class="filter-text" placeholder="Search" autofocus/>
    </x-heading>

    <div class="content">
        @foreach($bundles as $bundle)
            <h3 id="bundle-title-{{$bundle->slug}}" class="bundle-title"><a href="{{route('bundle.show', $bundle->slug)}}">{{ $bundle->display_name }}</a></h3>
            <div id="products-{{$bundle->slug}}" class="products">
                @foreach($bundle->products as $product)
                <x-card-product
                    :title="$product->display_name" 
                    :href="route('product.show', $product->slug)"
                    :countries="explode(',', $product->locations)"
                    :tags="[$product->group, $product->category]"
                    :data-title="$product->display_name"
                    :data-group="$product->group"
                    :data-locations="$product->locations">
                    {{ !empty($product->description)?$product->description:'View the product' }}
                </x-card-product>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection

@pushscript('products')
<script src="{{ mix('/js/templates/bundles/index.js') }}" defer></script>
@endpushscript