@extends('layouts.admin')

@section('title', $bundle->display_name)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('content')
<h1>{{ $bundle->display_name }}</h1>

<form id="admin-form" action="" method="POST">
    @method('PUT')
    @csrf
    <div class="editor-field">
        <h2>Content</h2>

        <div class="editor-field-label">
            <h3>Overview</h3>
            <div class="editor" data-input="body">{!! $content['overview'][0]['body'] ?? old('body') !!}</div>
        </div>

        <button class="button outline blue save-button">Apply changes</button>
    </div>
</form>
@endsection

@push('scripts')
<script src="{{ mix('/js/components/ckeditor.js') }}" defer></script>
@endpush