@extends('layouts.admin')

@section('title', $page->title)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/products/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/vendor/trix.css') }}">
<script src="{{ mix('/js/vendor/trix.js') }}"></script>
@endpush

@section('content')
<a class="button mb-2" href="{{ route('page.show', $page->slug) }}" target="_blank" rel="noreferrer">View page</a>

<form id="edit-form" action="{{ route('admin.page.update', $page->slug) }}" method="POST">

    @method('PUT')
    @csrf

    <input type="text" name="title" placeholder="Title" value="{{ $page['title'] }}">
    <input id="body" type="hidden" name="body" value="{{ $page['body'] }}">
    <trix-editor input="body"></trix-editor>

    <hr id="hr">
    <button>Update</button>

</form>
@endsection