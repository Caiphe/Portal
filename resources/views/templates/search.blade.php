@extends('layouts.master')

@section('title', "Searching for '{$searchTerm}'")

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/search.css') }}">
@endpush

@section('content')
    <x-heading heading="Search"></x-heading>
    @include('partials.search', [
        'action' => route('search'),
        'results' => $results
    ])
@endsection
