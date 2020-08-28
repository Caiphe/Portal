<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{"MTN":"Developer Portal"}</title>
    <link rel="icon" href="/images/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="{{ mix('/css/styles.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/templates/home.css') }}">
    <link rel="preload" href="/fonts/MTNBrighterSans-Regular.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/MTNBrighterSans-Bold.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/MTNBrighterSans-Medium.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/Montserrat-Regular.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/Montserrat-Light.woff2" as="font" type="font/woff2">
</head>
<body class="layout-home">
    <x-header/>

    <section class="banner-carousel">
        <x-carousel wait="5000" duration="0.34">
            <x-carousel-item>
            <div class="carousel-content">
                <h1>Create an account</h1>
                <p>Register your account if you want to create an App.</p>
                <a class="button after arrow-forward" href="/register" role="button">Register</a>
            </div>
            </x-carousel-item>

            <x-carousel-item>
                <div class="carousel-content">
                    <h1>Build an app</h1>
                    <p>When you have chosen the API you want to use to build your app, you need to register your app. Follow the steps below</p>
                    <a class="button after arrow-forward" href="/apps/create" role="button">Create</a>
                </div>
            </x-carousel-item>

            <x-carousel-item>
                <div class="carousel-content">
                    <h1 class="t-dark">Add products</h1>
                    <p class="t-dark">MTN is consistently developing new APIs for developers and businesses to create powerful products. Follow the steps to browse APIs</p>
                    <a class="button white after arrow-forward" href="/products" role="button">Add</a>
                </div>
            </x-carousel-item>

            <x-carousel-item>
                <div class="carousel-content">
                    <h1>Access the keys</h1>
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
                        <div class="col col-3">
                            <x-key-feature title="" icon="account-plus-outline">Sign-in/Register</x-key-feature>
                        </div>
                        <div class="col col-3">
                            <x-key-feature title="" icon="card-search">Browse our products</x-key-feature>
                        </div>
                        <div class="col col-3">
                            <x-key-feature title="" icon="plus-box-multiple">Register an app</x-key-feature>
                        </div>
                        <div class="col col-3">
                            <x-key-feature title="" icon="textbox-password">Access the keys</x-key-feature>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grey-bg">
        <div class="container flex">
            <div class="container-inner">
                <div class="cols">
                    <div class="col-6">
                        <div class="content-left">
                            <h1>Sign up</h1>
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

    <section>
        <div class="container flex">
            <div class="comtainer-inner">
                <div class="cols">
                    <div class="col col-6 col-image">
                        <div class="image-left">
                            <img src="/images/illustration-products.svg">
                        </div>
                    </div>
                    <div class="col col-6">
                        <div class="content-right">
                            <h1>Browse our products</h1>
                            <p class="t-pxl">
                                MTN is consistently developing new APIs for developers and businesses to create powerful products. After signing in, you can explore the list of available products and familiarise yourself with the documentaion and endpoints to determine if they would be a match for your next app.
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

    <section class="grey-bg">
        <div class="container flex">
            <div class="container-inner">
                <div class="cols">
                    <div class="col col-6">
                        <div class="content-left">
                            <h1>Register an app</h1>
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

    <section>
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
                            <h1>Access the keys</h1>
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

    <section class="blue-bg">
        <div class="container-flex">
            <div class="container">
                <h1 class="t-light">Product categories</h1>
                <div class="products-cards">
                    @foreach ($productsCollection as $product)
                        <x-card-icon :icon="\Illuminate\Support\Str::slug($product)" :title="$product" :href="'/products?category=' . $product"></x-card-icon>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- <section>
        <div class="container">
            <h1>Bundles</h1>
            <div class="bundle-cards">
                @foreach ($bundleCollection as $bundle)
                    @php //setting variables
                        $description = $bundle->description ?: 'View bundle';
                    @endphp
                        <x-card-product :title="$bundle->display_name" :href="route('bundle.show',$bundle->slug)"
                        :data-title="$bundle->display_name">@subStr($description)</x-card-product>
                    @endforeach
            </div>
            <div class="view-products">
                <a href="/bundles" class="button" role="button">View all bundles</a>
            </div>
        </div>
    </section> --}}

    <x-footer/>
    @stack("scripts")
</body>
</html>