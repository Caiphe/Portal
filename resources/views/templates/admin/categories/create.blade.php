@extends('layouts.admin')

@section('title', 'New category')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/products/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/vendor/trix.css') }}">
<script src="{{ mix('/js/vendor/trix.js') }}"></script>
@endpush

@section('content')
<form id="edit-form" action="{{ route('admin.category.store') }}" method="POST">

    @include('templates.admin.categories.form')

    <hr id="hr">
    <button>Create</button>

</form>
@endsection