@extends('layouts.admin')

@section('title', $bundle->display_name)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('content')
<a href="{{ url()->previous() }}" class="go-back">@svg('chevron-left') Back to Bundles</a>
<h1>{{ $bundle->title }}</h1>

<div class="page-actions">
    <a class="button outline dark" href="{{ route('bundle.show', $bundle->slug) }}" target="_blank" rel="noreferrer">View</a>
    <button id="save" class="button primary ml-1" form="admin-form">Save</button>
</div>

<form id="admin-form" action="{{ route('admin.bundle.update', $bundle->slug) }}" method="POST">
    @method('PUT')
    @csrf
    <div class="editor-field">
        <h2>Overview</h2>
        <div class="editor" data-input="body">{!! $content['overview'][0]['body'] ?? old('body') !!}</div>
    </div>
</form>
@endsection

@push('scripts')
<script src="{{ mix('/js/components/ckeditor.js') }}" defer></script>
@endpush