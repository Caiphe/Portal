@extends('layouts.sidebar')

@push('styles')
    <link rel="stylesheet" href="/css/templates/getting-started/index.css">
@endpush

@section('sidebar')
    <x-sidebar-accordion id="sidebar-accordion"  
    :list="
    [ 'GETTING STARTED' => 
        [
            [ 'label' => 'Introduction', 'link' => '#'],
            [ 'label' => 'My apps', 'link' => '#'],
            [ 'label' => 'Browse products','link' => '#'],
            [ 'label' => 'Create an application','link' => '#'],
            [ 'label' => 'Request approval','link' => '/request-approval'],
            [ 'label' => 'Responses and error codes','link' => '#'],
            [ 'label' => 'FAQ','link' => '#'],
            [ 'label' => 'Developer tips','link' => '#']
        ]
    ]" />   
@endsection

@section("content")
    <x-heading heading="Working with our products" tags="INTRODUCTION">
    </x-heading>
    <div class="getting-started">

        <h2 id="introduction">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam <a href="/getting-started#introduction">@svg('link', '#000000')</a></h2>
        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata.</p>
        
        <div id="product-usage">
            <h2 id="products-use">What you can do with our products <a href="/getting-started#products-use">@svg('link', '#000000')</a></h2>
            <x-card-link target="_blank" icon="apps-box" title="My Apps" linkUrl="http://www.google.com">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
            <x-card-link target="_blank" icon="card-search" title="Browse products" linkUrl="http://www.google.com">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
            <x-card-link target="_blank" icon="plus-circle-outline" title="Create an application" linkUrl="http://www.google.com">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
            <x-card-link target="_blank" icon="check-all" title="Request approval" linkUrl="http://www.google.com">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
            <x-card-link target="_blank" icon="code-json" title="Responses and error codes" linkUrl="http://www.google.com">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
            <x-card-link target="_blank" icon="help-network" title="FAQ" linkUrl="http://www.google.com">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
            <x-card-link target="_blank" icon="lightbulb" title="Developer tips" linkUrl="http://www.google.com">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
        </div>
    </div>
@endsection