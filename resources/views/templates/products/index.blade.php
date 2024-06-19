@extends('layouts.sidebar')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/products/index.css') }}">
@endpush

@section('title', 'Products')
<div class="view-country-body-container"></div>

@section('sidebar')
    <div class="filter-sidebar">

        <h2 class="filter-title-heading filter-show-mobile"> <img alt="filter-icon" src="/images/filter.svg" /> Filters</h2>
        <div class="filter-head">
            <h3>Categories</h3>
            <button class="clear-category custom-clear">Clear</button>
        </div>

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

        <div class="group-filter">

            <div class="filter-head">
                <h3>Group</h3>  
                <button class="clear-group custom-clear">Clear</button>
            </div>
            <div class="custom-select-block">
                <x-multiselect id="filter-group" name="filter-group" label="Select group" :options="$productGroups" />
                <img class="select-icon" src="/images/select-arrow.svg" />
            </div>
        </div>
        
        <div class="country-filter">
            <div class="filter-head">
                <h3>Country</h3>  
                <button class="clear-country custom-clear">Clear</button>
            </div>

            @foreach ($countries as $code => $name)
                <div class="filter-checkbox country-check-box-filters">
                    <input type="checkbox" name="{{ $code }}" id="country-{{ $code }}" class="filter-products filter-country" value="{{ $code }}" autocomplete="off" />
                    <label class="filter-label" for="country-{{ $code }}">
                        <img src="/images/locations/{{ $code }}.svg" title="{{ $name }} flag" alt="{{ $code }} flag">
                        {{ $name }}
                    </label>
                </div>
            @endforeach
        </div>

        <button id="filter-clear" class="dark outline"
            @isset($selectedCategory) style="display:block" @endisset>
            Clear filters
        </button>
    </div>
@endsection

@section('banner')
    <div id="banner">
        <div class="banner-content">
            <h1>{{ $content[0]['title'] }}</h1>
            <p>Browse <strong>{{ $products->count() }}</strong> products across <strong>{{ $countries->count() }}</strong> countries </p>
        </div>
    </div>
@endsection

@section('content')
{{-- 
    <div class="header-block">
        <h1>{{ $content[0]['title']}} <span class="available-products-count">({{ $products->count() }} available)</span></h1>
        {!! $content[0]['body'] !!}
        @svg('people', null, 'images/illustrations')
    </div>
 --}}

    <div class="content">
        <input type="text" name="filter-text" id="filter-text" class="filter-text" placeholder="search for products" autofocus="" autocomplete="off">
        <div class="products">
            @foreach ($productsCollection as $category => $products)
                <div class="category"
                     @if(isset($selectedCategory) && $selectedCategory !== $category)
                     style="display:none"
                    @endif
                >

                    <h3 class="category-title" data-category="{{ $products[0]->category_cid }}">{{ $category }} 
                        <div class="count-contenaire">
                            <span class="filters-count"></span>
                            <span class="header-count">{{ $products->count() }} products</span>
                        </div>
                    </h3>
                  
                    @foreach ($products as $product)
                        <x-card-product :title="$product->display_name"
                                        :href="route('product.show', $product->slug)"
                                        :countries="$product->countries->pluck('code', 'name')"
                                        :class="'access-' . $product->access"
                                        :tags="[$category]"
                                        target="_self"
                                        :data-title="$product->display_name"
                                        :data-group="$product->group"
                                        :data-access="$product->access"
                                        :data-category="$product->category_cid"
                                        :data-locations="$product->locations">{{ !empty($product->description)?$product->description:'View the product' }}
                        </x-card-product>
                    @endforeach
                </div>
            @endforeach
        </div>

        <div class="no-products-available">No products available. Please try other filters.</div>
    </div>


@endsection

@push('scripts')
<script src="{{ mix('/js/templates/products/index.js') }}" defer></script>
@endpush
