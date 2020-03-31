@extends('layouts.master')

@push('styles')
<link rel="stylesheet" href="/css/templates/home.css">
@endpush

@section('content')
<div class="banner-carousel">
<x-carousel wait="5000" duration="0.34">
        <x-carousel-item>
        <div class="carousel-content">
            <h1>Create an account</h1>
            <p>Register your account if you want to create an App.</p>
            <a class="button after arrow-forward" href="/register">Register</a>
        </div>
        </x-carousel-item>

        <x-carousel-item>
            <div class="carousel-content">
                <h1>Build an app</h1>
                <p>When you have chosen the API you want to use to build your app, you need to register your app. Follow the steps below</p>
                <a class="button after arrow-forward" href="/apps/create">Create</a>
            </div>
        </x-carousel-item>

        <x-carousel-item>
            <div class="carousel-content">
                <h1>Add products</h1>
                <p>MTN is consistently developing new APIs for developers and businesses to create powerful products. Follow the steps to browse APIs</p>
                <a class="button after arrow-forward" href="/products">Add</a>
            </div>
        </x-carousel-item>

        <x-carousel-item>
            <div class="carousel-content">
                <h1>Access the keys</h1>
                <p>Once your App submission is approved you can now use Consumer/API Key and Secret on your profile page.</p>
                <a class="button after arrow-forward" href="/apps">Access</a>
            </div>
        </x-carousel-item>
    </x-carousel>
</div>

    <div class="intro-steps section-padding">
        <div class="introduction">
            <h1>Get started using our products in 4 steps</h1>
            <p>You can browse our products and documentation without registering an account, but when you want to create an App you will need to register an account.</p>
        </div>
        <div class="steps">
            <x-key-feature title="" icon="account-plus-outline">Sign-in/Register</x-key-feature>
            <x-key-feature title="" icon="card-search">Browse our products</x-key-feature>
            <x-key-feature title="" icon="plus-box-multiple">Register an app</x-key-feature>
            <x-key-feature title="" icon="textbox-password">Access the keys</x-key-feature>
        </div>
    </div>

    <div class="sign-in grey-bg section-padding">
        <div class="content-left">
            <h1>Sign-in</h1>
            <p>Register your account if you want to create an App.</p>
            <ol>
                <li>Go to <strong>Register</strong></li> 
                <li>Complete the sign-up steps</li>
                <li>Agree to the T&C’s</li>
                <li>You’re in Sign in to register an App</li>
                <li>Go to <strong>‘Build an app’</strong></li>
            </ol>
            <a class="button" href="/register">View</a>
        </div>
        <div class="image-right"><img src="/images/register.png"></div>
    </div>

    <div class="browse-products section-padding">
        <div class="image-left"><img src="/images/products.png"></div>
        <div class="content-right">
            <h1>Browse our products</h1>
            <p>MTN is consistently developing new APIs for developers and businesses to create powerful products. Follow the steps to browse APIs</p>
            <ol>
                <li>Navigate to products</li>
                <li>Select a product that interests you</li>
                <li>Read the documentation on the page</li>
                <li>Review the endpoints</li>
            </ol>
            <a class="button" href="/products">View</a>
        </div>
    </div>

    <div class="register-app grey-bg section-padding">
        <div class="content-left">
            <h1>Register an app</h1>
            <p>When you have chosen the API you want to use to build your app, you need to register your app. Follow the steps below</p>
            <ol>
                <li>Go to Profile</li>
                <li>Click ‘Add a new app’ on the button as seen below​</li>
                <li>Fill in your App name, and optionally the Callback URL, and a description of your app</li>
                <li>Choose which APIs you require for your product</li>
                <li>Submit your request</li>
                <li>Await the approvals</li>
            </ol>
            <a class="button" href="/apps/create">View</a>
        </div>
        <div class="image-right"><img src="/images/register-app.png"></div>
    </div>

    <div class="access-keys section-padding">
        <div class="image-left"><img src="/images/access-key.png"></div>
        <div class="content-right">
            <h1>Access the keys</h1>
            <p>Once your App submission is approved you can now use Consumer/API Key and Secret on your profile page.</p>
            <ol>
                <li>For APIs that require API Key, use the Consumer Key as the x-api-key header</li>
                <li>For APIs that require OAuth, copy the key and secret and use them to generate an OAuth 2.0 access token, which is required to access MTN APIs.</li>
                <li>Information on our OAuth flow can be accessed on the OAuth page Browse APIs</li>
            </ol>
            <a class="button" href="/apps">View</a>
        </div>
    </div>

    <div class="latest-products grey-bg section-padding">
        <h1>Latest products</h1>
        <div class="products-cards">
            @foreach ($productsCollection as $product)
                @php //setting variables
                if ($product->locations !== 'all' && $product->locations !== null) :
                    $countries = explode(',',$product->locations);
                else :
                    $countries = array('globe');	
                endif;
                $tags = array($product->group,$product->category);
                $slug = 'products/'.$product->slug;
                @endphp
                    <x-card-product :title="$product->display_name" :href="'/' . $slug" :countries="$countries" :tags="$tags"
                    :data-title="$product->display_name"
                    :data-group="$product->group"
                    :data-locations="$product->locations">{{ !empty($product->description)?$product->description:'View the product' }}</x-card-product>
                @endforeach	
        </div>
        <div class="view-products"><a href="/products" class="button">View all products</a></div>
    </div>
@endsection 