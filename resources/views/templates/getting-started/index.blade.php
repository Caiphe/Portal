@extends('layouts.sidebar')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/getting-started/index.css') }}">
@endpush

@section('title', 'Getting started')

@section('sidebar')
    <x-sidebar-accordion id="sidebar-accordion" :active="'/' . request()->path()"
    :list="$list" />
@endsection

@section("content")
    <x-heading heading="Introduction" tags="Working with our products"></x-heading>

    <div class="content">

        <h2 id="introduction">Welcome to the MTN Developer Platform <a href="/getting-started#introduction">@svg('link', '#000000')</a></h2>
        <p>The MTN Developer Platform is a single point of access to a rich MTN developer ecosystem that brings a number of MTN services within easy reach of partners, startups, independent developers, enterprises, etc. Within this portal you will find access to our Mobile Money, Messaging, Location Services, Payment, Collections, E-Commerce, IoT,  Customer, Offers and Promotions, Digital Subscriptions, Identity and Access, Products and Bundles, and more API products.</p>
        <p>We hope that you will join us in developing new and novel customer experiences and solutions. If you see something wrong or think that we are missing an API or service that could make your next app idea rock, please reach out to our team on the forum - it's a great place to rally other developers to make yourself heard. If you are an ISV and are developing a solution for a client or integrating your commercial product to one of our APIs, we'd really like to hear from you too - let's see how we can help you succeed.</p>
        <p>As always, everything in life comes with <a href="/terms-and-conditions">terms and conditions</a>. We have tried to make this portal and the API products freely available for you to try.  Some APIs may be free to use, while some may carry a  prepaid, postpaid or even a pay as you go usage fee. Some APIs may require a formal agreement with MTN, while some may only require the acceptance of the product's terms of service. Some APIs may require your customer's consent, while some may require you to use our identity service. Most APIs will be standardised across all MTN Operating Companies, but there may be some that have country-specific peculiarities. At all times we will highlight these nuances so that you are always aware of them. If anything is not clear please reach out to us through the forum, where the answer to your question may help other developers as well.</p>
        <p>Take the time to explore the developer documentation. We have made every effort to explain how the developer site works, how to get started with your first app, some of the overarching design principles you will find in our APIs, our naming standards, and some key definitions. You will also find some examples that will help you through some of the challenges others have experienced, such as implementing some of the security requirements.  Above all... have fun and build exciting new digital experiences. We can't wait to see what you build.</p>

        <div class="product-usage">
            <h2 id="products-use">What you can do with our products <a href="/getting-started#products-use">@svg('link', '#000000')</a></h2>
            <x-card-link icon="apps-box" title="My Apps" href="/apps">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
            <x-card-link icon="card-search" title="Browse products" href="/products">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
            <x-card-link icon="plus-circle-outline" title="Create an application" href="/apps/create">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
            <x-card-link icon="check-all" title="Request approval" href="/request-approval">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
            <x-card-link icon="help-network" title="FAQ" href="/faq">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
            <x-card-link icon="lightbulb" title="Developer tips" href="/developer-tips">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>

            @foreach($content as $cardContent)
                @php
                    $cardLink = '/getting-started/' . $cardContent['slug'];
                    $contentBody = strip_tags($cardContent['body']);
                @endphp
                <x-card-link icon="apps-box" :title="$cardContent['title']" :href="$cardLink">{{ $contentBody }}</x-card-link>
            @endforeach
        </div>
    </div>
@endsection
