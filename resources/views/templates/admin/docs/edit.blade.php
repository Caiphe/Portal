@extends('layouts.admin')

@section('title', $doc->title)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('content')
<a href="{{ route('admin.doc.index') }}" class="go-back">@svg('chevron-left') Back to documentation</a>
<h1>{{ $doc->title }}</h1>

<div class="page-actions">
    <a class="button primary" href="{{ route('doc.show', $doc->slug) }}" target="_blank" rel="noreferrer">View documentation</a>
</div>

<form id="admin-form" action="{{ route('admin.doc.update', $doc->slug) }}" method="POST">
    @method('PUT')
    @include('templates.admin.docs.form', compact('doc'))
</form>
@endsection

@push('scripts')
<script src="{{ mix('/js/components/ckeditor.js') }}" defer></script>
@endpush