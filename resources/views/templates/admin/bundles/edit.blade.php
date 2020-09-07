@extends('layouts.admin')

@section('title', $bundle->display_name)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/products/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/vendor/trix.css') }}">
<script src="{{ mix('/js/vendor/trix.js') }}"></script>
@endpush

@section('content')
<form id="edit-form" action="{{ route('admin.bundle.update', $bundle->slug) }}" method="POST">

    @method('PUT')
    @csrf

    <label>
        <h2>Overview</h2>
        <input id="body" type="hidden" name="body" value="{{ $content['overview'][0]['body'] ?? '' }}">
        <trix-editor input="body"></trix-editor>
    </label>

    <hr id="hr">
    <button>Update</button>

</form>
@endsection