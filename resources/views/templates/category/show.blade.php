@extends('layouts.master-full-width')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/products/category.css') }}">
@endpush

@section('title', $category)

@section('main-class', $theme)

@section('content')
    <section class="container">
        <h1>
            {{ $category }}
            @if(\Auth::check() && \Auth::user()->can('view-admin'))
            <a href="{{ route('admin.category.edit', $slug) }}" class="edit button small dark outline">EDIT</a>
            @endif
        </h1>
        <div class="breadcrumb">
            <a href="{{ route('product.index', ['category' => $category]) }}">Products @svg('arrow-forward')</a>
        </div>
    </section>

    <section class="container banner">
        <h2 class="t-xlarge mb-3">{{ $content['heading'][0]['title'] ?? 'Content needed' }}</h2>
        {!! $content['heading'][0]['body'] ?? 'Content needed' !!}
        <div class="row mt-3">
            <a href="{{ route('product.index', ['category' => $category]) }}" class="button mr-1 view-products">View products @svg('arrow-forward')</a>
            <a href="{{ route('bundle.index') }}" class="button dark">View bundles @svg('arrow-forward', '#FFF')</a>
        </div>
        <img class="squiggle" src="/images/category/themes/{{ $theme }}/squiggle-hero.svg" alt="background squiggle">
        <div class="phone">
            <div class="banner-image" style="background-image: url('/images/category/banner-{{ $slug }}.jpg'), url('/images/category/banner-default.jpg');"></div>
            @svg('phone-outline', null, 'images/category')
        </div>
    </section>

    <section class="container businesses mb-5">
        {{-- <h2 class="mb-2">BUSINESSES THAT USES OUR PRODUCTS</h2>
        <img src="/images/businesses/acme-1.svg" alt="Acme logo">
        <img src="/images/businesses/acme-2.svg" alt="Acme logo">
        <img src="/images/businesses/acme-3.svg" alt="Acme logo">
        <img src="/images/businesses/acme-4.svg" alt="Acme logo">
        <img src="/images/businesses/acme-5.svg" alt="Acme logo">
        <img src="/images/businesses/acme-6.svg" alt="Acme logo">
        <img src="/images/businesses/acme-7.svg" alt="Acme logo">
        <img src="/images/businesses/acme-8.svg" alt="Acme logo"> --}}
    </section>

    <section class="grey-bg py-5">
        <div class="container overview">
            <h3 class="mt-5">{!! $content['benefits'][0]['title'] ?? 'Content needed' !!}</h3>
            <div class="benefits-copy">{!! $content['benefits'][0]['body'] ?? 'Content needed' !!}</div>

            <x-stack-general class="benefits" :cards="$content['product'] ?? []"></x-stack-general>

            <h3 class="mt-4">{!! $content['developer-centric'][0]['title'] ?? 'Content needed' !!}</h3>
            {!! $content['developer-centric'][0]['body'] ?? 'Content needed' !!}

            <hr>

            <div class="included">
                <div class="cols">
                    <div class="col-4">@svg('done') Endpoints</div>
                    <div class="col-4">@svg('done') Sandbox</div>
                </div>
                <div class="cols">
                    <div class="col-4">@svg('done') Extensive documentation</div>
                    <div class="col-4">@svg('done') Support</div>
                    <div class="col"><a href="{{ route('product.index', ['category' => $category]) }}" class="right-flex">View the docs @svg('arrow-forward')</a></div>
                </div>
            </div>
        </div>
    </section>

    <section class="yellow-bg py-5 example">
        <div class="container mt-5">
            <div class="snippets">
                <div class="tab">PHP</div>
                <div class="tab active">Curl</div>
                <div class="tab">Javascript</div>
                <div class="tab">C#</div>
                <div class="snippet">
                    <pre><code>curl -X POST -H "Content-Type: application/x-www-form-urlencoded" https://api.mtn.com/oauth/client_credential/accesstoken?
grant_type=client_credentials -d 'client_id={consumer-key}&client_secret={consumer-secret}'</code></pre></div>
            </div>
            <h2 class="t-large mb-1">Create an account</h2>
            <p>Register your account if you want to create an App.</p>
            <div class="cols">
                <a href="{{ route('register') }}" class="button dark mr-4">Register @svg('arrow-forward')</a>
                <a href="{{ route('doc.index') }}" class="button dark">Getting started @svg('arrow-forward')</a>
            </div>
        </div>
    </section>

    <section class="container relationships">
        @if(!empty($bundles))
        <div class="stack-bundle">
            <x-stack-cards class="bundle left" :cards="$bundles"></x-stack-cards>
            <div class="stack-description">
                <span class="tag yellow">MTN</span>
                <h3>{!! $content['bundles'][0]['title'] ?? 'Content needed' !!}</h3>
                {!! $content['bundles'][0]['body'] ?? 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Officiis commodi ipsum, ut ipsam, debitis incidunt dignissimos suscipit vitae reiciendis non, fugiat similique, deleniti nostrum aliquid voluptates enim mollitia sint ad.' !!}
                <a href="{{ route('bundle.index') }}">View all bundles @svg('arrow-forward')</a>
            </div>
        </div>
        @endif
        @if(!empty($products))
        <div class="stack-product">
            <div class="stack-description">
                <span class="tag yellow">MTN</span>
                <h3>{!! $content['products'][0]['title'] ?? 'Content needed' !!}</h3>
                {!! $content['products'][0]['body'] ?? 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Officiis commodi ipsum, ut ipsam, debitis incidunt dignissimos suscipit vitae reiciendis non, fugiat similique, deleniti nostrum aliquid voluptates enim mollitia sint ad.' !!}
                <a href="{{ route('product.index', ['category' => $category]) }}">View all products @svg('arrow-forward')</a>
            </div>
            <x-stack-cards class="product right" :cards="$products"></x-stack-cards>
        </div>
        @endif
    </section>

    {{-- <section class="container pricing">
        <h2 class="mt-1 centre">Pricing</h2>
        <p class="centre mb-5">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam<br>nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.</p>
        <x-pricing></x-pricing>
    </section> --}}
@endsection

@push('scripts')
<script src='https://cdn.polyfill.io/v3/polyfill.js?features=IntersectionObserver'></script>
<script>
    var observer = new IntersectionObserver(showStack, {
        root: null,
        rootMargin: '0px',
        threshold: 0.5
    });
    observer.observe(document.querySelector('.product'));
    // observer.observe(document.querySelector('.bundle'));
    // observer.observe(document.querySelector('.benefits'));
    // observer.observe(document.querySelector('.pricing'));

    function showStack(entries, observer) {
        entries.forEach(activate);
    }

    function activate(entry) {
        if(entry.isIntersecting) entry.target.classList.add('activate');
    }
</script>
@endpush