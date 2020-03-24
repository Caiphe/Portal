@extends('layouts.master')

@push('styles')
<link rel="stylesheet" href="/css/templates/products/index.css">
@endpush

@section('title', 'Products')

@section('content')
    @foreach ($products_collection as $category=>$products)
		<h3>{{ $category }}</h3>
		@foreach ($products as $product)
			<x-card-product :title="$product->display_name" class="product-block" :href="$product->slug" >{{ $product->description }}</x-card-product></div>
		@endforeach	
	@endforeach
@endsection