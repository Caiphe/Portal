@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/products/index.css') }}">
@endpush

@section('title', 'Products')

@section('content')
    <h1>Products</h1>
    <div class="products-number">Displaying <span class="display-count"> {{ $products->count() }} </span> of {{ $productsCount }} products</div>

    <x-admin.filter searchTitle="Product name">

        <label class="filter-item" for="access">
            Access level
            <select name="access" class="access-level" id="access-level">
                <option value="">All access levels</option>
                <option value="public" @if(request()->get('access') === 'public') selected @endif>Public</option>
                <option value="private" @if(request()->get('access') === 'private') selected @endif>Private</option>
            </select>
        </label>

        <label class="filter-item" for="product-group">
            Product Group
            <select class="product-group" id="product-group" name="group">
                <option value="">All product groups</option>
                @foreach ($productGroup as $group)
                <option value="{{ $group }}">{{ $group }}</option>
                @endforeach
            </select>
        </label>

        <label class="filter-item" for="product-category">
            Category
            <select class="product-category" id="product-category" name="category">
                <option value="">All product categories</option>
                @foreach ($productCategory as $category)
                <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>
        </label>

    </x-admin.filter>


    <div id="table-data">
        @include('components.admin.list', [
            'collection' => $products,
            'fields' => ['Name' => 'display_name', 'Access level' => 'access|addClass:not-on-mobile', 'Environments' => 'environments|splitToTag:,|addClass:not-on-mobile', 'Category' => 'category.title|addClass:not-on-mobile'],
            'modelName' => 'product'
        ])
    </div>
@endsection

@push('scripts')
<script src="{{ mix('/js/templates/admin/index.js') }}" defer></script>
<script>
    ajaxifyOnPopState = updateFilters;
    function updateFilters(params) {
        document.getElementById('search-page').value = params['q'] || '';
        document.querySelector('#access-level').value = params['access'] || '';
        document.getElementById('#product-group').value = params['group'] || '';
        document.getElementById('#product-category').value = params['category'] || '';
    }
</script>
@endpush
