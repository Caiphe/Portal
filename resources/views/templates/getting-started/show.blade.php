@extends('layouts.sidebar')

@push('styles')
    <link rel="stylesheet" href="/css/templates/getting-started/show.css">
@endpush

@section('sidebar')
    <x-sidebar-accordion id="sidebar-accordion" :active="'/' . request()->path()"  
    :list="
    [ 'GETTING STARTED' => 
        [
            [ 'label' => 'Introduction', 'link' => '/getting-started'],
            [ 'label' => 'My apps', 'link' => '/my-apps'],
            [ 'label' => 'Browse products','link' => '/browse-products'],
            [ 'label' => 'Create an application','link' => '/create-aplication'],
            [ 'label' => 'Request approval','link' => '/request-approval'],
            [ 'label' => 'Responses and error codes','link' => '/responses'],
            [ 'label' => 'FAQ','link' => '/faq'],
            [ 'label' => 'Developer tips','link' => '/developer-tips']
        ]
    ]" />  
@endsection

@section("content")
    <x-heading heading="Working with our products" tags="REQUEST APPROVAL">
    </x-heading>
    <div class="request-approval">
        <div class="section1">
            <h2 id="section1">Lorem ipsum dolor sit <a href="/request-approval#section1">@svg('link', '#000000')</a></h2>
            <p> Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. cing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
            <p> Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. cing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
        </div>
        <div classs="section2">
            <h2 id="section2">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam <a href="/request-approval#section2">@svg('link', '#000000')</a></h2>
            <p> Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. cing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
            <p> Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. cing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
        </div>
    </div>
@endsection