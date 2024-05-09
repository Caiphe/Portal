@extends('layouts.admin')

@section('title', 'Edit user')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/templates/admin/users/edit.css') }}">
@endpush

@section('content')
<a href="{{ route('admin.user.index') }}" class="go-back">@svg('chevron-left') Back to users</a>
<h1 class="header-username">{{ $user->full_name }}</h1>

<div class="page-actions">
    <a class="button primary" href="{{ route('admin.app.create', $user->id) }}">Create an app for this user</a>
</div>

<x-dialog-box class="admin-removal-confirm" dialogTitle="Confirm admin role removal">
    <div class="data-container">
        <span>Please note: By changing this role, you are changing access permissions to the user's account. The user might lose access to certain areas of the developer portal. <strong>Are you sure you would like to proceed ?</strong></span>
    </div>

    <div class="bottom-shadow-container button-container">
        <button type="button" class="primary" onclick="closeAdminRestore();">Proceed</button>
        <button type="button" class="cancel" onclick="closeDialogBox(this);">Cancel</button>
    </div>

</x-dialog-box>

{{-- confirm use's 2FA reset --}}
<x-dialog-box dialogTitle="Confirm 2FA reset" class="two-fa-modal-container">
    <div class="two-note">
        Would you like to confirm the reset of the user's 2FA ? They will be required to set it up on their next login.
    </div>

    <div class="bottom-shadow-container button-container">
        <form id="confirm-twofa-form" method="POST" action="{{ route('2fa.reset.confirm', $user) }}">
            @csrf
            <input type="hidden" name="user" value="{{ $user->id }}" />
            <button type="submit" id="confirm-two-fa-btn" class="btn primary">Confirm</button>
        </form>
    </div>
</x-dialog-box>

{{-- User Deletion request --}}
<x-dialog-box class="user-deletion-confirm" dialogTitle="Delete User">
    <div class="data-container">
        <span>
            You have requested a deletion of {{ $user->email }}. <br/>  
            A super admin will be notified
        </span>
    </div>

    <div class="bottom-shadow-container button-container">
        <form id="confirm-user-deletion-request-form" method="POST" action="{{ route('user.delete.request', $user) }}">
            @csrf
            <input type="hidden" name="user" value="{{ $user->id }}" />
            <button type="submit" id="confirm-user-deletion-btn" class="btn primary">Confirm</button>
        </form>
    </div>
</x-dialog-box>

<form id="admin-form" action="{{ route('admin.user.update', $user->slug) }}" method="POST">
    @method('PUT')
    @include('templates.admin.users.editform')
</form>
@endsection

@push('scripts')
    <script src="{{ mix('/js/templates/admin/users/edit-scripts.js') }}"></script>
    <script src="{{ mix('/js/components/emailValidation.js') }}"></script>
@endpush
