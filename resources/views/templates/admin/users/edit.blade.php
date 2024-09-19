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

{{-- Change user status modal --}}
<x-dialog-box dialogTitle="Change user status" class="user-status-modal-container">
    <div class="two-note">
        <p><strong>Are you sure you want to change this user's status?</strong></p> 
        <p>Inactive developers can still sign in to the developer portal and create apps; however, new application keys that are created won't be valid until this developer is set back to active.<p>
    </div>

    <div class="bottom-shadow-container button-container">
        <form id="change-user-status-form" method="POST" action="{{ route('admin.user.status', $user) }}">
            @method('POST')
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
            Are you sure you want to request a deletion of <strong> {{ $user->email }} </strong> ? <br/>  
            A super admin will be notified.
        </span>

        @if(count($isOwnerofTeams) > 0)
        <p class="team-owner-message">
            User is owner of below teams ,please delete the team (s) or remove the user from the team (s) before requesting the deletion :
        </p>
        <ul class="user-teams-list">
            @foreach($isOwnerofTeams as $team)
                <li>{{ $team }}</li>
            @endforeach
        </ul>
        @endif

    </div>

    <div class="bottom-shadow-container button-container">
        <form id="confirm-user-deletion-request-form" method="POST" action="{{ route('user.delete.request', $user) }}">
            @csrf
            <input type="hidden" name="user" value="{{ $user->id }}" />
            <button type="submit" id="confirm-user-deletion-request-btn" class="btn primary">Confirm</button>
            <button type="button" class="cancel" onclick="closeDialogBox(this);">Cancel</button>
        </form>
    </div>
</x-dialog-box>

{{-- User Deletion action --}}
<x-dialog-box class="user-deletion-action" dialogTitle="Delete User">
    <div class="data-container">
        <p>Are you sure you want to remove this user ?</p>
        <span><strong>Important Information:</strong></span>
        <br/>
        <p>Please note that this user will be completely removed from the portal.</p>
    </div>

    <div class="bottom-shadow-container">
        <form id="confirm-user-deletion-action-form" method="POST" action="{{ route('user.delete.action', $user) }}">
            @csrf
            <input type="hidden" name="user" value="{{ $user->id }}" />
            <input type="hidden" name="user_email" id="deletion_user_email" value="{{ $user->email }}" />

            <div class="button-container">
                <button type="submit" class="primary">Confirm</button>
                <button type="button" class="cancel" onclick="closeDialogBox(this);">Cancel</button>
            </div>
        </form>
    </div>
</x-dialog-box>

{{-- User verification modal --}}
<x-dialog-box dialogTitle="Verify User" class="verify-user-modal">
    <div class="two-note">
        <p>Are you sure you want to verify this user?</p>
    </div>

    <div class="bottom-shadow-container button-container">
        <form id="verify-user-form" method="post" action="{{ route('admin.user.verify', $user) }}">
            @method('PUT')
            @csrf

            <button type="submit"  class="btn primary">Confirm</button>
            <button type="button" class="cancel" onclick="closeDialogBox(this);">Cancel</button>
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
