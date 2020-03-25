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

    <button class="medium">OVERVIEW</button>
    <button class="medium">DOCS</button>
    <button class="medium">SPECIFICATION</button>
@endsection