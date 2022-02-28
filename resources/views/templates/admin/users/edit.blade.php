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

<form id="admin-form" action="{{ route('admin.user.update', $user->slug) }}" method="POST">
    @method('PUT')
    @include('templates.admin.users.editform')
</form>
@endsection
