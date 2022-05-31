@extends('layouts.admin')

@section('title', 'Edit user')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/templates/admin/users/edit.css') }}">
@endpush

@section('content')
<a href="{{ route('admin.user.index') }}" class="go-back">@svg('chevron-left') Back to users</a>
<h1>{{ $user->full_name }}</h1>

<div class="page-actions">
    <a class="button primary" href="{{ route('admin.app.create', $user->id) }}">Create an app for this user</a>
</div>

<x-dialog-box class="admin-removal-confirm" dialogTitle="Confirm admin role removal">
    <div class="data-container">
        <span>"Please note: By changing this role, you are changing access permissions to the user's account. The user might lose access to certain areas of the developer portal. <strong>Are you sure you would like to proceed ?</strong>"</span>
    </div>

    <div class="bottom-shadow-container button-container">
        <button type="button" class="primary" onclick="closeAdminRestore();">Proceed</button>
        <button type="button" class="cancel" onclick="adminRestore();">Cancel</button>
    </div>

</x-dialog-box>

<form id="admin-form" action="{{ route('admin.user.update', $user->slug) }}" method="POST">
    @method('PUT')
    @include('templates.admin.users.editform')
</form>
@endsection
