@extends('layouts.master')

@section('title', 'Searching for "' . Request::get('q', '') . '"')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/search.css') }}">
@endpush

@section('content')
    <h1>Search results</h1>
    @include('partials.search', [
        'action' => route('search'),
        'results' => $results
    ])
@endsection 