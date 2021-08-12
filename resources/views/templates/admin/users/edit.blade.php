@extends('layouts.admin')

@section('title', 'Edit user')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('page-info')
    <button id="save" class="outline dark ml-1" form="admin-form">Save</button>
@endsection

@section('content')
<form id="admin-form" action="{{ route('admin.user.update', $user->slug) }}" method="POST">

    @method('PUT')
    @include('templates.admin.users.form')

</form>
@endsection