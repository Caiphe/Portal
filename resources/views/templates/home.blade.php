@extends('layouts.base')

@section('body-class', 'layout-home')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/styles.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/templates/home.css') }}">
    <link rel="preload" href="/fonts/MTNBrighterSans-Regular.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/MTNBrighterSans-Bold.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/MTNBrighterSans-Medium.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/Montserrat-Regular.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/Montserrat-Light.woff2" as="font" type="font/woff2">
@endpush

@section('body')
    <x-header/>

    <section class="banner-carousel">
        <x-carousel wait="5000" duration="0.34">
            <x-carousel-item>
                <div class="carousel-content">
                    <h2>Create an account</h2>
                    <p>Register your account if you want to create an App.</p>
                    <a class="button after arrow-forward" href="/register" role="button">Register</a>
                </div>
            </x-carousel-item>

            <x-carousel-item>
                <div class="carousel-content">
                    <h2>Build an app</h2>
                    <p>When you have chosen the API you want to use to build your app, you need to register your app. Follow the steps below</p>
                    <a class="button after arrow-forward" href="/apps/create" role="button">Create</a>
                </div>
            </x-carousel-item>

            <x-carousel-item>
                <div class="carousel-content">
                    <h2 class="t-dark">Add products</h2>
                    <p class="t-dark">MTN is consistently developing new APIs for developers and businesses to create powerful products. Follow the steps to browse APIs</p>
                    <a class="button white after arrow-forward" href="/products" role="button">Add</a>
                </div>
            </x-carousel-item>

            <x-carousel-item>
                <div class="carousel-content">
                    <h2>Access the keys</h2>
                    <p>Once your App submission is approved you can now use Consumer/API Key and Secret on your profile page.</p>
                    <a class="button after arrow-forward" href="/apps" role="button">Access</a>
                </div>
            </x-carousel-item>
        </x-carousel>
    </section>

    <section class="introduction">
        <div class="container">
            <div>
                <h1>Get started using our products in 4 steps</h1>
                <p>You can browse our products and documentation without registering an account, but when you want to create an App you will need to register an account.</p>
            </div>
            <div class="steps">
                <div class="container-inner">
                    <div class="cols">
                        <a href="#section-sign-up" class="col col-3">
                            <x-key-feature title="" icon="account-plus-outline">Sign-in/Register</x-key-feature>
                        </a>
                        <a href="#section-browse-our-products" class="col col-3">
                            <x-key-feature title="" icon="card-search">Browse our products</x-key-feature>
                        </a>
                        <a href="#section-register-an-app" class="col col-3">
                            <x-key-feature title="" icon="plus-box-multiple">Register an app</x-key-feature>
                        </a>
                        <a href="#section-access-the-keys" class="col col-3">
                            <x-key-feature title="" icon="textbox-password">Access the keys</x-key-feature>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="section-sign-up" class="grey-bg">
        <div class="container flex">
            <div class="container-inner">
                <div class="cols">
                    <div class="col-6">
                        <div class="content-left">
                            <h2>Sign up</h2>
                            <p class="t-pxl">To be able to create an app, you would need to register an account first. Simply follow the registration process and you’ll be good to go.</p>
                            <span class="t-ps-header">Steps involved</span>
                            <ol>
                                <li>Complete the registration steps</li>
                                <li>Verify your email address</li>
                            </ol>
                            <a class="button" href="/register" role="button">Get started</a>
                        </div>
                    </div>
                    <div class="col-6 col-image">
                        <div class="image-right">
                            <img src="/images/illustration-sign-up.svg">
                        </div>
                   </div>
               </div>
           </div>
       </div>
    </section>

    <section id="section-browse-our-products">
        <div class="container flex">
            <div class="container-inner">
                <div class="cols">
                    <div class="col col-6 col-image">
                        <div class="image-left">
                            <img src="/images/illustration-products.svg">
                        </div>
                    </div>
                    <div class="col col-6">
                        <div class="content-right">
                            <h2>Browse our products</h2>
                            <p class="t-pxl">
                                MTN is consistently developing new APIs for developers and businesses to create powerful products. After signing in, you can explore the list of available products and familiarise yourself with the documentation and endpoints to determine if they would be a match for your next app.
                            </p>
                            <span class="t-ps-header">Steps involved</span>
                            <ol>
                                <li>Explore available products</li>
                                <li>Review the documentation and endpoints</li>
                            </ol>
                            <a class="button" href="/products" role="button">Browse products</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="section-register-an-app" class="grey-bg">
        <div class="container flex">
            <div class="container-inner">
                <div class="cols">
                    <div class="col col-6">
                        <div class="content-left">
                            <h2>Register an app</h2>
                            <p class="t-pxl">
                                Now that you’re more familiar with our products, you can proceed to registering your app. Simply click on “Build app” and follow the on-screen instructions to complete the app registration.
                            </p>
                            <span class="t-ps-header">Steps involved</span>
                            <ol>
                                <li>Supply your app details</li>
                                <li>Select the regions for your app</li>
                                <li>Enable the available products for your app</li>
                            </ol>
                            <a class="button" href="/apps/create" role="button">Create app</a>
                        </div>
                    </div>
                    <div class="col col-6 col-image">
                        <div class="image-right">
                            <img src="/images/illustration--apps.svg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="section-access-the-keys">
        <div class="container flex">
            <div class="container-inner">
                <div class="cols">
                    <div class="col col-6 col-image">
                        <div class="image-left">
                            <img src="/images/illustration-keys.svg">
                        </div>
                    </div>
                    <div class="col col-6">
                        <div class="content-right">
                            <h2>Access the keys</h2>
                            <p class="t-pxl">
                                Once your app has been approved, you can access all your app’s details such as consumer/secret keys and more from your app dashboard.                </p>
                            <span class="t-ps-header">Steps involved</span>
                            <ol>
                                <li>Access your app dashboard under your profile</li>
                                <li>Review your app details under the “Approved apps” section</li>
                            </ol>
                            <a class="button" href="/apps" role="button">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="section-product-categories" class="blue-bg">
        <div class="container-flex">
            <div class="container">
                <h2 class="t-light">Product categories</h2>
                <div class="products-cards">
                    @foreach($globalCategories->shuffle()->take(6) as $category)
                        <x-card-icon :icon="$category->slug" :title="$category->title" :href="route('category.show', $category->slug)"></x-card-icon>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <x-footer/>
@endsection
