@extends('layouts.admin')

@section('title', 'New category')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('content')
<a href="{{ url()->previous() }}" class="go-back">@svg('chevron-left') Back to categories</a>
<h1>Create category</h1>

<form id="admin-form" action="{{ route('admin.category.store') }}" method="POST">
    @include('templates.admin.categories.form')
</form>
@endsection

@push('scripts')
<script src="{{ mix('/js/components/ckeditor.js') }}" defer></script>
@endpush