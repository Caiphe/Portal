@extends('layouts.admin')

@section('title', $doc->title)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/products/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/vendor/trix.css') }}">
<script src="{{ mix('/js/vendor/trix.js') }}"></script>
@endpush

@section('content')
<a class="button mb-2" href="{{ route('doc.show', $doc->slug) }}" target="_blank" rel="noreferrer">View page</a>

<form id="edit-form" action="{{ route('admin.doc.update', $doc->slug) }}" method="POST">

    @method('PUT')
    @csrf

    <input type="text" name="title" placeholder="Title" value="{{ $doc['title'] }}">
    <input id="body" type="hidden" name="body" value="{{ $doc['body'] }}">
    <trix-editor input="body"></trix-editor>

    <hr id="hr">
    <button>Update</button>

</form>
@endsection