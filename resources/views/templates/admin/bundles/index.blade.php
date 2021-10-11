@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
@endpush

@section('title', 'Bundles')

@section('content')
    <x-admin.table :collection="$bundles" model-name="bundle" :fields="['display_name', 'category.title']"></x-admin-table>
@endsection

@push('scripts')
<script>
    ajaxifyOnPopState = updateFilters;
    function updateFilters(params) {
        document.getElementById('search-page').value = params['q'] || '';
    }

</script>
@endpush