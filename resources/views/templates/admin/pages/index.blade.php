@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
@endpush

@section('title', 'Pages')

@section('content')
    <h1>Pages</h1>

    <div class="page-actions">
        <a href="{{ route('admin.page.create') }}" class="button primary page-actions-create" aria-label="Create page"></a>
    </div>

    <x-admin.filter searchTitle="Page name"></x-admin.filter>

    <div id="table-data">
        @include('components.admin.list', [
            'collection' => $pages,
            'fields' => ['Title' => 'title', 'Published' => 'published_at|date:d M Y|addClass:not-on-mobile'],
            'modelName' => 'page'
        ])
    </div>
@endsection

@push('scripts')
<script src="{{ mix('/js/templates/admin/index.js') }}" defer></script>
@endpush