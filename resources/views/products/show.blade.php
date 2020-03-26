@extends('layouts.sidebar')

@push('styles')
<link rel="stylesheet" href="/css/templates/products/show.css">
@endpush

@section('sidebar')
    <x-sidebar-accordion id="product-page-sidebar" :active="'/' . request()->path()" :list="$sidebarAccordion"/>
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

    <div id="product-sections" class="{{$startingPoint}}">
        @if(isset($content['product_overview']))
        <button id="button-overview" class="light small product-section-button" onclick="switchSection('product-overview');">OVERVIEW</button>
        @endif
        @if(isset($content['product_docs']))
        <button id="button-docs" class="light small product-section-button" onclick="switchSection('product-docs');">DOCS</button>
        @endif
        <button id="button-specification" class="light small product-section-button" onclick="switchSection('product-specification');">SPECIFICATION</button>
            
        @if(isset($content['product_overview']))
        <div id="product-overview" class="product-section">
            {!!$product->content[0]['body']!!}
            <div class="key-features">
                @foreach($product->keyFeatures as $keyFeature)
                <x-key-feature :title="$keyFeature['title']">
                    {{$keyFeature['description'] ?? ''}}
                </x-key-feature>
                @endforeach
            </div>
        </div>
        @endif

        @if(isset($content['product_docs']))
        <div id="product-docs" class="product-section">
            {!!$product->content[1]['body']!!}
        </div>
        @endif

        <div id="product-specification" class="product-section">
            <h2 class="mt-0">Download</h2>
            <a href="{{ route('product.download.postman', [$product->slug]) }}" class="button">Download Postman collection</a>
            <a href="{{ route('product.download.swagger', [$product->slug]) }}" class="button">Download Swagger</a>
            
            <h2 class="mt-4">Available endpoints</h2>
            @foreach($specification['item'] as $spec)
            <div class="specification-detail">
                <div class="endpoint" onclick="toggleParent(this)">
                    @svg('chevron-right') <span class="tag {{strtolower($spec['request']['method'])}}">{{strtoupper($spec['request']['method'])}}</span> {{implode('/', $spec['request']['url']['path'])}}
                </div>

                @if(!empty($spec['description']))
                <h4>Description</h4>
                @endif
                <p class="description my-0">{{$spec['description']}}</p>

                <h4>Header parameters</h4>
                @foreach($spec['request']['header'] as $parameter)
                <x-products.parameter :title="$parameter['name']" :type="$parameter['type']" :required="$parameter['required'] ?? 0">{{$parameter['description']}}</x-products.parameter>
                @endforeach
                
                @if(isset($spec['request']['url']['query']))
                <h4>Query parameters</h4>
                @foreach($spec['request']['url']['query'] as $parameter)
                <x-products.parameter :title="$parameter['key']" :type="$parameter['type']" :required="$parameter['required'] ?? 0">{{$parameter['description']}}</x-products.parameter>
                @endforeach
                @endif
                
                @if(isset($spec['request']['body']))
                <h4>FormData parameters</h4>
                @foreach($spec['request']['body']['formdata'] as $parameter)
                <x-products.parameter :title="$parameter['key']" :type="$parameter['type']" :required="$parameter['required'] ?? 0">{{$parameter['description']}}</x-products.parameter>
                @endforeach
                @endif
            </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
<script src="/js/templates/products/show.js" defer></script>
@endpush