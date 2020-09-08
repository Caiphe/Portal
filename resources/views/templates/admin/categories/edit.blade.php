@extends('layouts.admin')

@section('title', $category->title)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/products/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/vendor/trix.css') }}">
<script src="{{ mix('/js/vendor/trix.js') }}"></script>
@endpush

@section('content')
<a class="button mb-2" href="{{ route('category.show', $category->slug) }}" target="_blank" rel="noreferrer">View category</a>

<form id="edit-form" action="{{ route('admin.category.update', $category->slug) }}" method="POST">

    @method('PUT')
    @include('templates.admin.categories.form', compact('category', 'content'))

    <hr id="hr">
    <button>Update</button>

</form>
@endsection