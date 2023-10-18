@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
@endpush

@section('title', 'Categories')

@section('content')
<h1>Categories</h1>
    <x-admin.filter searchTitle="Category name"></x-admin.filter>

    <div id="table-data">
        @include('components.admin.list', [
            'collection' => $categories,
            'fields' => ['Title' => 'title', 'Theme' => 'theme|addClass:not-on-mobile'],
            'modelName' => 'category'
        ])
    </div>
@endsection

@push('scripts')
<script src="{{ mix('/js/templates/admin/index.js') }}" defer></script>
<script>
    ajaxifyOnPopState = updateFilters;
    function updateFilters(params) {
        document.getElementById('search-page').value = params['q'] || '';
    }
</script>
@endpush