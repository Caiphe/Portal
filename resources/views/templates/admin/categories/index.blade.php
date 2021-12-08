@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
@endpush

@section('title', 'Categories')

@section('content')
<h1>Categories</h1>
    <x-admin.filter searchTitle="Category name"></x-admin.filter>

    <div class="page-actions">
        <a href="{{ route('admin.category.create') }}" class="button primary">Create</a>
    </div>

    <div id="table-data">
        @include('components.admin.list', [
            'collection' => $categories,
            'fields' => ['title', 'theme'],
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