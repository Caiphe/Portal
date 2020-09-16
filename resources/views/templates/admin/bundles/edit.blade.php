@extends('layouts.admin')

@section('title', $bundle->display_name)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/vendor/trix.css') }}">
<script src="{{ mix('/js/vendor/trix.js') }}"></script>
@endpush

@section('page-info')
    <a class="button outline dark" href="{{ route('bundle.show', $bundle->slug) }}" target="_blank" rel="noreferrer">View</a>
    <button id="save" class="outline dark ml-1">Save</button>
@endsection

@section('content')
<form id="edit-form" action="{{ route('admin.bundle.update', $bundle->slug) }}" method="POST">

    @method('PUT')
    @csrf

    <div class="editor-field">
        <h2>Overview</h2>
        <input id="body" type="hidden" name="body" value="{{ $content['overview'][0]['body'] ?? '' }}">
        <trix-editor input="body"></trix-editor>
    </div>

</form>
@endsection

@push('scripts')
    <script src="{{ mix('/js/templates/admin/edit.js') }}"></script>
@endpush