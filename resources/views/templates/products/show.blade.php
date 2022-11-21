@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/vendor/stoplight.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/templates/products/show.css') }}">
    <script src="{{ mix('/js/vendor/stoplight.js') }}"></script>
    @if(!empty($content))
        <style>
            @foreach($content['all'] as $tab)
    #product-sections.product-{{$tab->slug}} #product-{{$tab->slug}}{
                display: block;
            }
            #product-sections.product-{{$tab->slug}} #button-{{$tab->slug}},
            #product-sections.product-{{$tab->slug}} #button-{{$tab->slug}} {
                background-color: #666;
                color: #FFF;
            }
            #product-sections.product-{{$tab->slug}} #button-{{$tab->slug}}:hover,
            #product-sections.product-{{$tab->slug}} #button-{{$tab->slug}}:hover {
                background-color: #858585;
                border-color: #858585;
                color: #FFF;
            }
            @endforeach
        </style>
    @endif
@endpush

@section('title', $product->display_name)

@section('content')
    <x-heading :heading="$product->display_name" :breadcrumbs="['Products' => route('product.index'), $product->category->title => route('category.show', $product->category->slug)]" :edit="route('admin.product.edit', $product->slug)">
        <div class="available-in">
            <h4>AVAILABLE IN</h4>
            <div class="flags">
                @if($product->locations === 'all')
                    <img class="flag" src="/images/locations/globe.svg" alt="All countries" title="All countries">
                @else
                    @foreach($product->countries->pluck('code', 'name') as $name => $code)
                        <img class="flag" src="/images/locations/{{ $code }}.svg" alt="{{ $name }}" title="{{ $name }}">
                    @endforeach
                @endif
            </div>
        </div>
    </x-heading>

    <div class="content">
        <div id="product-sections" class="{{ $startingPoint }}">
            <div class="create-app-from-product-swagger-btn">
                @if($specification)
                    <a href="{{ route('product.download.swagger', [$product->slug]) }}" class="button">Download Swagger</a>
                @endif
                <a class="button create-app-from-product" href="{{ route('app.create', ['product' => $product->slug]) }}" role="button">Create app with product</a>
            </div>

            @foreach($content['lhs'] as $tab)
                @if($tab->body)
                    <button id="button-{{$tab->slug}}" class="light small outline product-section-button" onclick="switchSection('product-{{$tab->slug}}');">{{strtoupper($tab->title)}}</button>
                @endif
            @endforeach
            <button id="button-specification" class="light small outline product-section-button" onclick="switchSection('product-specification');">SPECIFICATION</button>
            @foreach($content['rhs'] as $tab)
                <button id="button-{{$tab->slug}}" class="light small outline product-section-button" onclick="switchSection('product-{{$tab->slug}}');">{{strtoupper($tab->title)}}</button>
            @endforeach

            <div id="product-specification" class="product-section">
                @if($specification)
                    {{-- <a href="{{ route('product.download.postman', [$product->slug]) }}" class="button">Download Postman collection</a> --}}

                    <h2 class="mt-4">Available endpoints</h2>
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
