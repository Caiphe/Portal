@extends('layouts.master-full-width')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/products/category.css') }}">
@endpush

@section('title', $category)

@section('main-class', $theme)

@section('content')
    <section class="container">
        <h1>{{ $category }}</h1>
        <div class="breadcrumb">
            <a href="#overview">Overview</a>
            <a href="{{ route('product.index', ['category' => $category]) }}">Products @svg('arrow-forward')</a>
        </div>
    </section>

    <section class="container banner">
        <h2 class="t-xlarge mb-3">Power your apps with our MTN MoMo API</h2>
        <p class="my-0 t-medium">Learn the basics of MTN MoMo API, view available resources and join a community of developers building with the MoMo API.</p>
        <div class="row mt-3">
            <a href="{{ route('product.index', ['category' => $category]) }}" class="button mr-1 view-products">View products @svg('arrow-forward')</a>
            <button class="dark after arrow-right">Button</button>
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
                <a href="{{ route('getting-started') }}" class="button dark">Getting started @svg('arrow-forward')</a>
            </div>
        </div>
    </section>

    <section class="container relationships">
        <div class="stack-bundle">
            <x-stack-cards class="bundle left" :cards="[
                [
                    'name' => 'FINTECH',
                    'group' => 'MTN',
                    'description' => 'The Fintech bundle allows you to communcate with those who need to keep up to date with what is going on.',
                    'countries' => ['za' => 'South Africa', 'ug' => 'Uganda', 'rw' => 'Rwanda', 'mu' => 'Mauritius', 'ma' => 'Morocco', 'my' => 'Malaysia', 'na' => 'Namibia', 'ne' => 'Niger(the)', 'ng' => 'Nigeria', 'mz' => 'Mozambique', 'st' => 'Sao Tome and Principe', 'sn' => 'Senegal', 'sc' => 'Seychelles', 'mg' => 'Madagascar', 'sb' => 'Solomon Islands', 'so' => 'Somalia', 'sl' => 'Sierra Leone'],
                    'href' => route('bundle.index')
                ],
                [
                    'name' => 'FINTECH',
                    'group' => 'MTN',
                    'description' => 'The Fintech bundle allows you to communcate with those who need to keep up to date with what is going on.',
                    'countries' => ['za' => 'South Africa', 'ug' => 'Uganda', 'rw' => 'Rwanda', 'mu' => 'Mauritius', 'ma' => 'Morocco', 'my' => 'Malaysia', 'na' => 'Namibia', 'ne' => 'Niger(the)', 'ng' => 'Nigeria', 'mz' => 'Mozambique', 'st' => 'Sao Tome and Principe', 'sn' => 'Senegal', 'sc' => 'Seychelles', 'mg' => 'Madagascar', 'sb' => 'Solomon Islands', 'so' => 'Somalia', 'sl' => 'Sierra Leone'],
                    'href' => route('bundle.index')
                ],
                [
                    'name' => 'FINTECH',
                    'group' => 'MTN',
                    'description' => 'The Fintech bundle allows you to communcate with those who need to keep up to date with what is going on.',
                    'countries' => ['za' => 'South Africa', 'ug' => 'Uganda', 'rw' => 'Rwanda', 'mu' => 'Mauritius', 'ma' => 'Morocco', 'my' => 'Malaysia', 'na' => 'Namibia', 'ne' => 'Niger(the)', 'ng' => 'Nigeria', 'mz' => 'Mozambique', 'st' => 'Sao Tome and Principe', 'sn' => 'Senegal', 'sc' => 'Seychelles', 'mg' => 'Madagascar', 'sb' => 'Solomon Islands', 'so' => 'Somalia', 'sl' => 'Sierra Leone'],
                    'href' => route('bundle.index')
                ]
            ]"></x-stack-cards>
            <div class="stack-description">
                <span class="tag yellow">MTN</span>
                <h3>Bundles</h3>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sint quas maxime inventore aspernatur dolorum, obcaecati iure rem totam accusantium provident tempora corrupti consequatur adipisci voluptatum quae modi facilis doloremque deleniti?</p>
                <a href="{{ route('bundle.index') }}">View all bundles @svg('arrow-forward')</a>
            </div>
        </div>
        <div class="stack-product">
            <div class="stack-description">
                <span class="tag yellow">MTN</span>
                <h3>Products</h3>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sint quas maxime inventore aspernatur dolorum, obcaecati iure rem totam accusantium provident tempora corrupti consequatur adipisci voluptatum quae modi facilis doloremque deleniti?</p>
                <a href="{{ route('product.index', ['category' => $category]) }}">View all products @svg('arrow-forward')</a>
            </div>
            <x-stack-cards class="product right" :cards="[
                [
                    'name' => 'SMS',
                    'group' => 'MTN',
                    'description' => 'The SMS product allows you to communcate with those who need to keep up to date with what is going on.',
                    'countries' => ['lr' => 'Liberia', 'ly' => 'Libya', 'mg' => 'Madagascar', 'mw' => 'Malawi', 'my' => 'Malaysia', 'mu' => 'Mauritius', 'ma' => 'Morocco', 'mz' => 'Mozambique', 'na' => 'Namibia', 'ne' => 'Niger(the)', 'ng' => 'Nigeria', 'rw' => 'Rwanda', 'st' => 'Sao Tome and Principe', 'sn' => 'Senegal', 'sc' => 'Seychelles', 'sl' => 'Sierra Leone', 'sb' => 'Solomon Islands', 'so' => 'Somalia', 'za' => 'South Africa'],
                    'href' => route('product.show', 'test-product-1')
                ],
                [
                    'name' => 'SMS',
                    'group' => 'MTN',
                    'description' => 'The SMS product allows you to communcate with those who need to keep up to date with what is going on.',
                    'countries' => ['lr' => 'Liberia', 'ly' => 'Libya', 'mg' => 'Madagascar', 'mw' => 'Malawi', 'my' => 'Malaysia', 'mu' => 'Mauritius', 'ma' => 'Morocco', 'mz' => 'Mozambique', 'na' => 'Namibia', 'ne' => 'Niger(the)', 'ng' => 'Nigeria', 'rw' => 'Rwanda', 'st' => 'Sao Tome and Principe', 'sn' => 'Senegal', 'sc' => 'Seychelles', 'sl' => 'Sierra Leone', 'sb' => 'Solomon Islands', 'so' => 'Somalia', 'za' => 'South Africa'],
                    'href' => route('product.show', 'test-product-1')
                ],
                [
                    'name' => 'SMS',
                    'group' => 'MTN',
                    'description' => 'The SMS product allows you to communcate with those who need to keep up to date with what is going on.',
                    'countries' => ['lr' => 'Liberia', 'ly' => 'Libya', 'mg' => 'Madagascar', 'mw' => 'Malawi', 'my' => 'Malaysia', 'mu' => 'Mauritius', 'ma' => 'Morocco', 'mz' => 'Mozambique', 'na' => 'Namibia', 'ne' => 'Niger(the)', 'ng' => 'Nigeria', 'rw' => 'Rwanda', 'st' => 'Sao Tome and Principe', 'sn' => 'Senegal', 'sc' => 'Seychelles', 'sl' => 'Sierra Leone', 'sb' => 'Solomon Islands', 'so' => 'Somalia', 'za' => 'South Africa'],
                    'href' => route('product.show', 'test-product-1')
                ]
            ]"
            ></x-stack-cards>
        </div>
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
    observer.observe(document.querySelector('.bundle'));
    observer.observe(document.querySelector('.product'));
    observer.observe(document.querySelector('.benefits'));
    observer.observe(document.querySelector('.pricing'));

    function showStack(entries, observer) {
        entries.forEach(activate);
    }

    function activate(entry) {
        if(entry.isIntersecting) entry.target.classList.add('activate');
    }
</script>
@endpush