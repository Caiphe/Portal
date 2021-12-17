@extends('layouts.admin')

@section('title', 'Searching for "' . Request::get('q', '') . '"')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/search.css') }}">
<link rel="stylesheet" href="{{ mix('/css/templates/admin/search.css') }}">
@endpush

@section('content')
<h1>Search</h1>

@include('partials.search', [
    'action' => route('admin.search'),
    'results' => $results
])
@endsection