@extends('layouts.admin')

@section('title', $category->title)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('content')
<a href="{{ route('admin.category.index') }}" class="go-back">@svg('chevron-left') Back to categories</a>
<h1>{{ $category->title }}</h1>

<div class="page-actions">
    <a class="button primary" href="{{ route('category.show', $category->slug) }}" target="_blank" rel="noreferrer">View category</a>
</div>

<form id="admin-form" action="{{ route('admin.category.update', $category->slug) }}" method="POST">
    @method('PUT')
    @include('templates.admin.categories.form', compact('category', 'content'))
</form>
@endsection

@push('scripts')
<script src="{{ mix('/js/components/ckeditor.js') }}" defer></script>
@endpush