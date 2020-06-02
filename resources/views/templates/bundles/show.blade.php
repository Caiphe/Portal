@extends('layouts.sidebar')

@push('styles')
<link rel="stylesheet" href="{{mix('/css/templates/bundles/show.css')}}">
@endpush

@section('title', $bundle['display_name'])

@section('banner')
<div id="banner" style="background-image: url({{$bundle->banner}});"></div>
@endsection

@section('sidebar')
<ul id="sidebar-nav">
    @foreach($sidebar as $menuItem)
    <li>
        <a href="{{route('bundle.show', $menuItem['slug'])}}">
            @if(request()->path() === 'bundles/' . $menuItem['slug']) @svg('arrow-forward', '#0f6987') @endif
            {{$menuItem['name']}}
        </a>
    </li>
    @endforeach
</ul>
@endsection

@section('content')
    <div class="heading-box">
        <h1>{{$bundle->display_name}}</h1>
        <p class="t-pxl">{{$bundle->description}}</p>
        <a href="/" class="button yellow outline">Subscribe</a>
    </div>

    <div class="price-box">
        <h3>$6,250</h3>
        <small>PER MONTH</small>
        <p>25,000<sup>1</sup></p>
        <small class="border-bottom">transactions per month</small>
        <p>0.31¢</p>
        <small class="border-bottom">per transaction credit</small>
        <p>Features</p>
        <small>
            No monthly contracts<br>
            Business support<br>
            Online Technical Support<br>
            Other premium Servicess<br>
        </small>
    </div>

    <div class="available-in bold t-small">
        <h4>Available in</h4>
        @forelse($countries as $code => $country)
        <img src="/images/locations/{{$code}}.svg" alt="{{$country}}" title="{{$country}}">
        @empty
        <img src="/images/locations/all.svg" alt="All" title="All">
        @endforelse
    </div>

    @if(isset($content['bundle_overview']))
    <h2>Overview</h2>
    {{$content['bundle_overview'][0]['body']}}
    @endif

    @if(!$bundle->keyFeatures->isEmpty())
    <h2>Key features</h2>
    <div class="cols centre-flex mt-3">
    @foreach($bundle->keyFeatures as $keyFeature)
        <x-key-feature :title="$keyFeature['title']" class="px-2">
            {{$keyFeature['description'] ?? ''}}
        </x-key-feature>
    @endforeach
    </div>
    @endif

    <h2>Prerequires</h2>
    <p>oAuth 2</p>

    <h2>Products included</h2>
    <div class="products">
        @foreach($bundle->products as $product)
        <x-card-product
            :title="$product->display_name" 
            :href="route('product.show', $product->slug)"
            :countries="explode(',', $product->locations)"
            :tags="[$product->group, $product->category->title]"
            :data-title="$product->display_name"
            :data-group="$product->group"
            :data-locations="$product->locations">
            {{ !empty($product->description)?$product->description:'View the product' }}
        </x-card-product>
        @endforeach
    </div>
@endsection