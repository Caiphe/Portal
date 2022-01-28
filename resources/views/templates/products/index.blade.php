@extends('layouts.sidebar')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/products/index.css') }}">
@endpush

@section('title', 'Products')

@section('sidebar')
    <div class="filter-sidebar">
        <input type="text" name="filter-text" id="filter-text" class="filter-text" placeholder="Search" autofocus autocomplete="off" />

        <h3>Categories</h3>
        @foreach ($productCategories as $slug => $title)
            <div class="filter-checkbox">
                <input type="checkbox" name="{{ $slug }}" id="category-{{ $slug }}" class="filter-products filter-category" value="{{ $slug }}" @if(isset($selectedCategory) && $selectedCategory === $title) checked=checked @endif autocomplete="off" />
                <label class="filter-label" for="category-{{ $slug }}">{{ $title }}</label>
            </div>
        @endforeach
        @if($hasPrivateProduct || $hasInternalProduct)
            <h3>Access</h3>
            @if($hasInternalProduct)
                <div class="filter-checkbox">
                    <input class="filter-products filter-access" type="checkbox" id="access-internal" name="access[]" value="internal" autocomplete="off" />
                    <label class="filter-label" for="access-internal">Internal</label>
                </div>
            @endif
            @if($hasPrivateProduct)
                <div class="filter-checkbox">
                    <input class="filter-products filter-access" type="checkbox" id="access-private" name="access[]" value="private" autocomplete="off" />
                    <label class="filter-label" for="access-private">Private</label>
                </div>
            @endif
        @endif
        <div class="country-filter">
            <h3>Country</h3>
            <x-multiselect id="filter-country" name="filter-country" label="Select country" :options="$countries" />
        </div>
        <button id="filter-clear" class="dark outline"
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
        <h1>{{ $content[0]['title']}}</h1>
        {!! $content[0]['body'] !!}
        @svg('people', null, 'images/illustrations')
    </div>

    <div class="content">
        <div class="products">
            @foreach ($productsCollection as $category => $products)
                <div class="category"
                     @if(isset($selectedCategory) && $selectedCategory !== $category)
                     style="display:none"
                    @endif
                >
                    <h3 class="category-title" data-category="{{ $products[0]->category_cid }}">{{ $category }}</h3>
                    @foreach ($products as $product)
                        <x-card-product :title="$product->display_name"
                                        :href="route('product.show', $product->slug)"
                                        :countries="$product->countries->pluck('code', 'name')"
                                        :class="'access-' . $product->access"
                                        :tags="[$product->group, $category]"
                                        :data-title="$product->display_name"
                                        :data-group="$product->group"
                                        :data-access="$product->access"
                                        :data-category="$product->category_cid"
                                        :data-locations="$product->locations">{{ !empty($product->description)?$product->description:'View the product' }}</x-card-product>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endsection

@pushscript('products')
<script src="{{ mix('/js/templates/products/index.js') }}" defer></script>
@endpushscript
