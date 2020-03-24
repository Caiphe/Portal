@extends('layouts.master')

@push('styles')
<link rel="stylesheet" href="/css/templates/products/index.css">
@endpush


<x-heading heading="Products"></x-heading>

@section('content')
    @foreach ($products_collection as $category=>$products)
		<h3>{{ $category }}</h3>
		@foreach ($products as $product)
			@php //setting variables
			if ($product->locations !== 'all' || $product->locations !== NULL) :
				$countries = explode(',',$product->locations);
			endif;
			$tags = array($product->group,$product->category);
			@endphp
			<x-card-product :title="$product->display_name" class="product-block" :href="$product->slug" :countries="$countries" :tags="$tags">{{ $product->description }}</x-card-product></div>
		@endforeach	
	@endforeach
@endsection