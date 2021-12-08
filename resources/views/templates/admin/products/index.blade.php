@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/products/index.css') }}">
@endpush

@section('title', 'Products')

@section('content')
    <h1>Products</h1>

    <x-admin.filter searchTitle="Product name">
        <label class="filter-item" for="access">
            Access
            <select name="access" class="access-level" id="access-level">
                <option value="">All access levels</option>
                <option value="public" @if(request()->get('access') === 'public') selected @endif>Public</option>
                <option value="private" @if(request()->get('access') === 'private') selected @endif>Private</option>
            </select>
        </label>
    </x-admin.filter>

    <div id="table-data">
        @include('components.admin.list', [
            'collection' => $products,
            'fields' => ['Name' => 'display_name', 'Access' => 'access', 'Environments' => 'environments,splitToTag:,', 'Category' => 'category.title'],
            'modelName' => 'product'
        ])
    </div>
@endsection

@push('scripts')
<script src="{{ mix('/js/templates/admin/index.js') }}"></script>
<script>
    ajaxifyOnPopState = updateFilters;
    function updateFilters(params) {
        document.getElementById('search-page').value = params['q'] || '';
        document.querySelector('#access-level').value = params['access'] || '';
    }
</script>
@endpush
