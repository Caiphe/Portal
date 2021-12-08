@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
@endpush

@section('title', 'Documentation')

@section('content')
    <h1>Documentation</h1>

    <div class="page-actions">
        <a href="{{ route('admin.doc.create') }}" class="button primary">Create</a>
    </div>

    <x-admin.filter searchTitle="Documentation name"></x-admin.filter>

    <div id="table-data">
        @include('components.admin.list', [
            'collection' => $docs,
            'fields' => ['title'],
            'modelName' => 'doc'
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