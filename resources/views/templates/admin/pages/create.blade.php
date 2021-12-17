@extends('layouts.admin')

@section('title', 'Create page')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('content')
<a href="{{ url()->previous() }}" class="go-back">@svg('chevron-left') Back to pages</a>
<h1>Create Page</h1>

<form id="admin-form" action="{{ route('admin.page.store') }}" method="POST">
    @include('templates.admin.pages.form')
</form>
@endsection

@push('scripts')
<script src="{{ mix('/js/components/ckeditor.js') }}" defer></script>
@endpush