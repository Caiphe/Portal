@extends('layouts.sidebar')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/products/show.css') }}">
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

@section('sidebar')
    <x-sidebar-accordion id="product-page-sidebar" :active="'/' . request()->path()" :list="$sidebarAccordion"/>
@endsection

@section('content')
    <x-heading :heading="$product->display_name" :edit="route('admin.product.edit', $product->slug)">
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
        <div id="product-sections" class="{{$startingPoint}}">
            @foreach($content['lhs'] as $tab)
            <button id="button-{{$tab->slug}}" class="light small outline product-section-button" onclick="switchSection('product-{{$tab->slug}}');">{{strtoupper($tab->title)}}</button>
            @endforeach
            <button id="button-specification" class="light small outline product-section-button" onclick="switchSection('product-specification');">SPECIFICATION</button>
            @foreach($content['rhs'] as $tab)
            <button id="button-{{$tab->slug}}" class="light small outline product-section-button" onclick="switchSection('product-{{$tab->slug}}');">{{strtoupper($tab->title)}}</button>
            @endforeach

            <div id="product-specification" class="product-section">
                @if(!is_null($specification))
                <h2 class="mt-0">Download</h2>
                <a href="{{ route('product.download.postman', [$product->slug]) }}" class="button">Download Postman collection</a>
                <a href="{{ route('product.download.swagger', [$product->slug]) }}" class="button">Download Swagger</a>

                <h2 class="mt-4">Available endpoints</h2>
                @foreach($specification['item'] as $spec)
                    <div class="specification-detail">
                        <div class="endpoint" onclick="toggleParent(this)">
                            @svg('chevron-right') <span class="tag {{strtolower($spec['request']['method'])}}">{{strtoupper($spec['request']['method'])}}</span> {{implode('/', $spec['request']['url']['path'])}}

                            @if(!empty($spec['description']))
                            <p class="specification-description">{{ $spec['description'] }}</p>
                            @endif
                        </div>

                        <x-products.options :request="$spec['request']"/>

                        <x-products.breakdown :responses="$spec['response']" :request="$spec['request']"/>
                    </div>
                @endforeach
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
