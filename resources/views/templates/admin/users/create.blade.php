@extends('layouts.admin')

@section('title', 'New user')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/templates/admin/users/edit.css') }}">
@endpush

@section('content')
<a href="{{ url()->previous() }}" class="go-back">@svg('chevron-left') Back to users</a>
<h1>Create user</h1>

<div class="page-actions">
    <button id="save" class="button primary" form="admin-form">Save</button>
</div>

<form id="admin-form" action="{{ route('admin.user.store') }}" method="POST">
    @include('templates.admin.users.form')
</form>
@endsection