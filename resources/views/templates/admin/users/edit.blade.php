@extends('layouts.admin')

@section('title', 'Edit user')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('page-info')
    <a class="button primary" href="{{ route('admin.app.create', $user->id) }}">Create an app for this user</a>
    <button id="save" class="outline dark ml-1" form="admin-form">Save</button>
@endsection

@section('content')

    <div class="parent-container">
        <form id="admin-form" action="{{ route('admin.user.update', $user->slug) }}" method="POST">

            @method('PUT')
            @include('templates.admin.users.editform')

        </form>
    </div>

@endsection
