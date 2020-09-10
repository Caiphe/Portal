@extends('layouts.admin')

@section('title', 'Searching for "' . Request::get('q', '') . '"')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/search.css') }}">
@endpush

@section('content')
    @include('partials.search', [
        'action' => route('admin.search'),
        'results' => $results
    ])
@endsection