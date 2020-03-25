@extends('layouts.sidebar')

@push('styles')
<link rel="stylesheet" href="/css/templates/products/index.css">
@endpush

@section('sidebar')
@endsection

@section('content')
	<x-heading heading="Products"></x-heading>
	<div class="container">
	@foreach ($productsCollection as $category=>$products)
		<div class="row">
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
				<x-card-product :title="$product->display_name" class="product-block" :href="$product->slug" :countries="$countries" :tags="$tags">{{ $product->description }}</x-card-product>
			@endforeach	
		</div>
	@endforeach
	</div>
@endsection