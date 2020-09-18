@extends('layouts.admin')

@section('title', 'New user')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('page-info')
    <button id="save" class="outline dark" form="admin-form">Save</button>
@endsection

@section('content')
<form id="admin-form" action="{{ route('admin.user.store') }}" method="POST">

    @include('templates.admin.users.form')

</form>
@endsection