@extends('layouts.sidebar')

@push('styles')
<link rel="stylesheet" href="/css/templates/products/show.css">
@endpush

@section('sidebar')
    @php
        $list = [ 
            'Customer' => [
                ['label' => 'APN', 'link' => 'https://www.google.com/'],
                ['label' => 'Devices','link' => 'https://www.google.com/'],
                ['label' => 'KYC','link' => 'https://www.google.com/']
            ],
        ];
    @endphp
    <x-sidebar-accordion id="product-page-sidebar" :list="$list"/>
@endsection

@section('content')
    <x-heading :heading="$product->display_name" fab="dark plus">
        <div class="available-in">
            <h4>AVAILABLE IN</h4>
            <div class="flags">
                @foreach(preg_split('/,\s?/', $product['locations']) as $location)
                <img class="flag" src="/images/locations/{{$location}}.svg" alt="{{$location}}" title="{{$location}}">
                @endforeach
            </div>
        </div>
    </x-heading>

    <button class="light small product-section-button">OVERVIEW</button>
    <button class="outline light small product-section-button">DOCS</button>
    <button class="outline light small product-section-button">SPECIFICATION</button>

    <div id="product-overview" class="product-section">
        <p>product-overview</p>
    </div>
    <div id="product-docs" class="product-section">
        <p>product-docs</p>
    </div>
    <div id="product-specification" class="product-section">
        <p>product-specification</p>
    </div>
@endsection