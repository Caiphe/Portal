@extends('layouts.master')

@push('styles')
<link rel="stylesheet" href="/css/templates/search.css">
@endpush

@section('content')
    <h1>Search results</h1>
    <input class="search" placeholder="Search term" >
    <div class="search-results">
        <p><strong>123 </strong>results for <strong>Search term</strong></p>
        <x-card-search  title="Title" link="http://www.google.com">Lorem ipsum dolor sit amet, consetetur sadipscing elit. </x-card-search>
        <x-card-search  title="Title" link="http://www.google.com">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores.</x-card-search>
        <x-card-search  title="Title" link="http://www.google.com">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores.</x-card-search>
        <x-card-search  title="Title" link="http://www.google.com">This is the description</x-card-search>
        <x-card-search  title="Title" link="http://www.google.com">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores.</x-card-search>
        <x-card-search  title="Title" link="http://www.google.com">This is the description</x-card-search>
    </div>

@endsection 