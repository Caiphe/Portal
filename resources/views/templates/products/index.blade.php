@extends('layouts.sidebar')

@push('styles')
<link rel="stylesheet" href="/css/templates/products/index.css">
@endpush

@section('sidebar')
@php
$filters = array('Group'=> array('MTN'),'Categories'=> $productCategories);
@endphp
<h2>Filter by</h2>

@foreach ($filters as $filterTitle => $filterGroup)
	<h3>{{$filterTitle}}</h3>
	@foreach ($filterGroup as $filterItem)
		<div class="filter-checkbox">
			<input type="checkbox" name="{{$filterTitle}}" value="{{$filterItem}}" id="{{$filterTitle}}" onclick="filterProducts('{{$filterTitle}}');"/><label class="filter-label" for="{{$filterTitle}}">{{$filterItem}}</label>
		</div>
	@endforeach	
@endforeach
<div class="country-filter">
	<h3>Country</h3>
	<x-multiselect id="filter-country" name="filter-country" label="Select country" :options="$countries" />
</div>

<button id="clearFilter" class="dark outline" onclick="clearFilter()">Clear filters</button>
@endsection

@section('content')
	<x-heading heading="Products"></x-heading>
	<div class="container">
	@foreach ($productsCollection as $category=>$products)
	<div class="row product-category" data-category="{{ $category }}">
			<h3>{{ $category }}</h3>
			@foreach ($products as $product)
				@php //setting variables
				if ($product->locations !== 'all' && $product->locations !== null) :
					$countries = explode(',',$product->locations);
				else :
					$countries = array('globe');	
				endif;
				$tags = array($product->group,$product->category);
				@endphp
				<x-card-product :title="$product->display_name" class="product-block" :href="$product->slug" :countries="$countries" :tags="$tags"
				:data-title="$product->display_name"
				:data-group="$product->group"
				:data-locations="$product->locations">{{ !empty($product->description)?$product->description:'View the product' }}</x-card-product>
			@endforeach	
		</div>
	@endforeach
	</div>
@endsection

@pushscript('products')
<script src="/js/templates/products/index.js" defer></script>
@endpushscript
