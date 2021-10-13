@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
@endpush

@section('title', 'Products')

@section('content')
    <x-admin.table :collection="$products" model-name="product" :fields="['display_name', 'access', 'environments', 'category.title']"></x-admin-table>
@endsection

@push('scripts')
<script>
    document.getElementById('access-level').addEventListener('change', accessChangedFilter);

    function accessChangedFilter() {
        var filterForm = document.getElementById('product-search-form');

        if(filterForm.requestSubmit !== undefined) {
            filterForm.requestSubmit();
        }else{
            filterForm.submit();
        }
    }

    ajaxifyOnPopState = updateFilters;
    function updateFilters(params) {
        document.getElementById('search-page').value = params['q'] || '';
        document.querySelector('#access-level').value = params['access'] || '';
    }

</script>
@endpush
