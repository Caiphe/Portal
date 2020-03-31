@extends('layouts.sidebar')

@push('styles')
    <link rel="stylesheet" href="/css/templates/getting-started/index.css">
@endpush

@section('title', 'Getting started')

@section('sidebar')
    <x-sidebar-accordion id="sidebar-accordion" :active="'/' . request()->path()"
    :list="$list" />   
@endsection

@section("content")
    <x-heading heading="Introduction" tags="Working with our products">
    </x-heading>
    <div class="getting-started">

        <h2 id="introduction">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam <a href="/getting-started#introduction">@svg('link', '#000000')</a></h2>
        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata.</p>
        
        <div id="product-usage">
            <h2 id="products-use">What you can do with our products <a href="/getting-started#products-use">@svg('link', '#000000')</a></h2>
            <x-card-link icon="apps-box" title="My Apps" href="/apps">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
            <x-card-link icon="card-search" title="Browse products" href="/products">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
            <x-card-link icon="plus-circle-outline" title="Create an application" href="/apps/create">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
            <x-card-link icon="check-all" title="Request approval" href="/request-approval">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
            <x-card-link icon="code-json" title="Responses and error codes" href="/getting-started/response-and-error-codes">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
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