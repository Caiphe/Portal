@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/vendor/stoplight.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/templates/products/show.css') }}">
    <script src="{{ mix('/js/vendor/stoplight.js') }}"></script>
    @if(!empty($content))
        {{-- <style>
            @foreach($content['all'] as $tab)
            #product-sections.product-{{$tab->slug}} #product-{{$tab->slug}}{
                display: block;
            }
            @endforeach
        </style> --}}
    @endif
@endpush

@section('title', $product->display_name)

@section('content')
    <x-heading :heading="$product->display_name" :breadcrumbs="['Products' => route('product.index'), $product->category->title => route('category.show', $product->category->slug)]" :edit="route('admin.product.edit', $product->slug)"></x-heading>

    <div class="content">
        <div id="product-sections" class="{{ $startingPoint }}">

            <div class="product-top-block">
                <div class="each-product-block">
                    @if($product->description)
                    <p>{{ $product->description }}</p>
                    @endif

                    <p>This product is available for these countries</p>

                    <div class="flags">
                        @if($product->locations === 'all')
                        <div class="each-country">
                            <img class="flag" src="/images/locations/globe.svg" alt="All countries" title="All countries">
                        </div>
                        @else
                            @foreach($product->countries->pluck('code', 'name') as $name => $code)
                            <div class="each-country">
                                <img class="flag" src="/images/locations/{{ $code }}.svg" alt="{{ $name }}" title="{{ $name }}">
                                <span class="country-name">{{ $name }}</span>

                            </div>
                            @endforeach
                        @endif
                    </div>

                </div>
                <div class="each-product-block">
                    <div class="each-call-to-action">
                        <div class="left-data">
                            <h5 class="block-header">Quick start</h5>
                            <p>Use Notification {{ $product->display_name }} in your own app for the data</p>
                        </div>
                        <a class="button dark outline action-btn-product" href="{{ route('app.create', ['product' => $product->slug]) }}" role="button">Create app with product</a>
                    </div>

                    @if($specification)
                    <div class="each-call-to-action">
                        <div class="left-data">
                            <h5 class="block-header">Advanced</h5>
                            <p>Try an example YAML to get you started quickly</p>
                        </div>

                        <a class="button dark outline action-btn-product" href="{{ route('product.download.swagger', [$product->slug]) }}">Download Swagger</a>
                    </div>
                    @endif

                    @foreach($content['lhs'] as $tab)
                    @if($tab->body && strtoupper($tab->title) === "DOCS")

                    <div class="each-call-to-action">
                        <div class="left-data">
                            <h5 class="block-header">Docs</h5>
                            <p>Learn how to use this product in your apps</p>
                        </div>
                        <button id="button-{{$tab->slug}}" class="button dark outline action-btn-product" onclick="switchSection('product-{{$tab->slug}}');">Read Docs</button>
                    </div>

                    @endif
                    @endforeach

                </div>
            </div>
            
            <button id="button-specification" class="product-section-button" onclick="switchSection('product-specification');">Specifications</button>
            
            <div id="product-specification" class="product-section">

                @if($specification)
                    {{-- <a href="{{ route('product.download.postman', [$product->slug]) }}" class="button">Download Postman collection</a> --}}

                    <h2 class="endpoint-available">Available endpoints</h2>
                    <div id="elements-api-desktop">
                        <elements-api
                            apiDescriptionUrl="{{ asset("openapi/{$product->swagger}") }}"
                            router="memory"
                        />
                    </div>
                    <div id="elements-api-mobile">
                        <elements-api
                            apiDescriptionUrl="{{ asset("openapi/{$product->swagger}") }}"
                            router="memory"
                            layout="stacked"
                        />
                    </div>
                @else
                    <div class="no-specification">
                        <p>NO CONTENT FOUND</p>
                        <h2>COMING SOON</h2>
                        <a href="{{ route('product.index') }}" class="button outline white">BROWSE OUR PRODUCTS</a>
                    </div>
                @endif
            </div>

            @foreach($content['all'] as $tab)
                <div id="product-{{$tab->slug}}" class="product-section">
                    {!!$tab->body!!}
                </div>
                @if(isset($content['Overview']))
                    <div class="key-features">
                        @foreach($product->keyFeatures as $keyFeature)
                            <x-key-feature :title="$keyFeature['title']">
                                {{$keyFeature['description'] ?? ''}}
                            </x-key-feature>
                        @endforeach
                    </div>
                @endif
            @endforeach

            @if(!empty($alternatives))
                <div class="alternatives">
                    @if(is_null($specification))
                        <h3>We don't have anything at the moment, but we do have similar products below!</h3>
                    @else
                        <h3>Check out some of our other products.</h3>
                    @endif
                    <div class="alternative-cards">
                        @foreach($alternatives as $product)
                            <x-card-product :title="$product->display_name"
                                            :href="route('product.show', $product->slug)"
                                            :countries="explode(',', $product->locations)"
                                            :tags="[$product->group]"
                                            :data-title="$product->display_name"
                                            :data-group="$product->group"
                                            :data-locations="$product->locations"
                            >
                                {{ !empty($product->description)?$product->description:'View the product' }}
                            </x-card-product>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('/js/templates/products/show.js') }}" defer></script>
@endpush
