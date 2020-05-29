@extends('layouts.master-full-width')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/products/category.css') }}">
@endpush

@section('title', $category)

@section('main-class', $theme)

@section('content')
    <section class="container inset">
        <h1>{{ $category }}</h1>
        <div class="breadcrumb">
            <a href="#overview">Overview</a>
            <a href="{{ route('product.index', ['category' => $category]) }}">Products @svg('arrow-forward')</a>
        </div>
    </section>
    <section class="container inset banner">
        <h2 class="t-xlarge mb-3">Power your apps with our MTN MoMo API</h2>
        <p class="my-0 t-medium">Learn the basics of MTN MoMo API, view available resources and join a community of developers building with the MoMo API.</p>
        <div class="row mt-3">
            <a href="{{ route('product.index', ['category' => $category]) }}" class="button after arrow-right mr-1">View products</a>
            <button class="dark after arrow-right">Button</button>
        </div>
        <img class="squiggle" src="/images/category/themes/{{ $theme }}/squiggle-hero.svg" alt="background squiggle">
        <div class="phone">
            <img src="/images/category/banner-business.jpg" alt="Phone background">
            @svg('phone-outline', null, 'images/category')
        </div>
    </section>
    <section class="container inset businesses mb-5">
        <h2 class="mb-2">BUSINESSES THAT USES OUR PRODUCTS</h2>
        <img src="/images/businesses/acme-1.svg" alt="Acme logo">
        <img src="/images/businesses/acme-2.svg" alt="Acme logo">
        <img src="/images/businesses/acme-3.svg" alt="Acme logo">
        <img src="/images/businesses/acme-4.svg" alt="Acme logo">
        <img src="/images/businesses/acme-5.svg" alt="Acme logo">
        <img src="/images/businesses/acme-6.svg" alt="Acme logo">
        <img src="/images/businesses/acme-7.svg" alt="Acme logo">
        <img src="/images/businesses/acme-8.svg" alt="Acme logo">
    </section>
    <section class="grey-bg py-5">
        <div class="container inset overview">
            <h3 class="mt-5">Benefits</h3>
            <p class="benefits-copy">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no.</p>

            <x-stack-general class="benefits"></x-stack-general>

            <h3 class="mt-4">Developer-centric</h3>
            <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no.</p>

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
    <section class="yellow-bg py-5">
        <div class="container inset example mt-5">
            <div class="snippets">
                <div class="tab">PHP</div>
                <div class="tab active">Curl</div>
                <div class="tab">Javascript</div>
                <div class="tab">C#</div>
                <div class="snippet">
                    <pre><code>curl -X POST -H "Content-Type: application/x-www-form-urlencoded" https://api.mtn.com/oauth/client_credential/accesstoken?
grant_type=client_credentials -d 'client_id={consumer-key}&client_secret={consumer-secret}'</code></pre></div>
            </div>
            <h2 class="t-large mb-1">Lorem ipsum dolor sit amet</h2>
            <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr.</p>
            <div class="cols">
                <a href="#" class="button dark after arrow-right mr-4">Button</a>
                <a href="#" class="button dark after arrow-right">Button</a>
            </div>
        </div>
    </section>
    <section class="container inset pricing">
        <h2 class="mt-1 centre">Pricing</h2>
        <p class="centre">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam<br>nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.</p>
        <x-pricing></x-pricing>
    </section>
@endsection