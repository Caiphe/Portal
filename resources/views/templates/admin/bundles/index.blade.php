@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
@endpush

@section('title', 'Bundles')

@section('content')
    <h1>Bundles</h1>

    <x-admin.filter searchTitle="Bundle name"></x-admin.filter>

    <div id="table-data">
        @include('components.admin.list', [
            'collection' => $bundles,
            'fields' => ['Name' => 'display_name', 'Category' => 'category.title|addClass:not-on-mobile'],
            'modelName' => 'bundle'
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