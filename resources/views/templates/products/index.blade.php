@extends('layouts.sidebar')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/products/index.css') }}">
@endpush

@section('title', 'Products')

@section('sidebar')
@php
$filters = array('Group'=> $groups,'Categories'=> $productCategories);
@endphp
<div class="filter-sidebar">
	@foreach ($filters as $filterTitle => $filterGroup)
		<h3>{{$filterTitle}}</h3>
		@foreach ($filterGroup as $filterItem)
			<div class="filter-checkbox">
				<input type="checkbox" name="{{$filterTitle}}" value="{{$filterItem}}" id="{{$filterItem}}" onchange="filterProducts('{{$filterTitle}}');" @if(isset($selectedCategory) && $selectedCategory===$filterItem) checked=checked @endif />
				<label class="filter-label" for="{{$filterItem}}">{{$filterItem}}</label>
			</div>
		@endforeach
	@endforeach
	<div class="country-filter">
		<h3>Country</h3>
		<x-multiselect id="filter-country" name="filter-country" label="Select country" :options="$countries" />
	</div>

	<button id="clearFilter" class="dark outline" onclick="clearFilter()"
	@isset($selectedCategory)
		style="display:block"
	@endisset>Clear filters</button>
</div>
@endsection

@section('content')
    <x-heading heading="Products">
        <input type="text" name="filter-text" id="filter-text" class="filter-text" placeholder="Search" autofocus/>
    </x-heading>
    <div class="content">
        <div class="products">
            @foreach ($productsCollection as $category => $products)
            <div class="category" data-category="{{ $category }}"
				@if(isset($selectedCategory) && $selectedCategory !== $category)
				style="display:none"
				@endif
            >
                <h3 class="category-title">{{ $category }}</h3>
                @php
                    $products = $products->sortBy('display_name');
                @endphp
                @foreach ($products as $product)
                @php //setting variables
					if ($product->locations !== 'all' && $product->locations !== null) :
						$countries = explode(',',$product->locations);
					else :
						$countries = array('globe');
					endif;
					$tags = [$product->group, $category];
                @endphp
                <x-card-product :title="$product->display_name"
                				:href="route('product.show', $product->slug)"
                				:countries="$countries"
                				:tags="$tags"
                                :data-title="$product->display_name"
                                :data-group="$product->group"
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
