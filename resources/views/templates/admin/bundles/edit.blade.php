@extends('layouts.admin')

@section('title', $bundle->display_name)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('content')
<a href="{{ route('admin.bundle.index') }}" class="go-back">@svg('chevron-left') Back to bundles</a>
<h1>{{ $bundle->display_name }}</h1>

<div class="page-actions">
    <a class="button primary" href="{{ route('bundle.show', $bundle->slug) }}" target="_blank" rel="noreferrer">View bundle</a>
</div>

<form id="admin-form" action="{{ route('admin.bundle.update', $bundle->slug) }}" method="POST">
    @method('PUT')
    @csrf
    <div class="editor-field">
        <h2>Content</h2>

        <label class="editor-field-label">
            <h3>Overview</h3>
            <div class="editor" data-input="body">{!! $content['overview'][0]['body'] ?? old('body') !!}</div>
        </label>

        <button class="button outline blue save-button">Apply changes</button>
    </div>
</form>
@endsection

@push('scripts')
<script src="{{ mix('/js/components/ckeditor.js') }}" defer></script>
@endpush