@php
    $user = auth()->user();
@endphp
@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/teams/create.css') }}">
@endpush

@extends('layouts.sidebar')

@section('sidebar')

    <x-sidebar-accordion id="sidebar-accordion" active="/teams" :list="
    [ 'Manage' =>
        [
            [ 'label' => 'My profile', 'link' => '/profile'],
            [ 'label' => 'My apps', 'link' => '/apps'],
            [ 'label' => 'My teams', 'link' => '/teams']
        ],
        'Discover' =>
        [
            [ 'label' => 'Browse all products', 'link' => '/products'],
            [ 'label' => 'Working with our products','link' => '/getting-started'],
        ]
    ]
    " />
@endsection

@section('title')
   Create team
@endsection

@section('content')

    <x-heading heading="My teams" tags="Dashboard"></x-heading>

    <x-twofa-warning class="tall"></x-twofa-warning>

    @if ($teamInvite && !$team->hasUser($user))
    {{-- Top ownerhip block container --}}
    <div class="top-invite-banner show">
        <div class="message-container">You have been requested to be part of {{ $team->name }}.</div>
        <div class="btn-block-container">
            {{--  Use the accept endpoint --}}
            <button type="button" class="btn blue-button dark-accept accept-team-invite" data-invitetoken="{{ $teamInvite->accept_token }}" >Accept request</button>
            {{--  Use the revoke endpoint --}}
            <button type="button" class="btn blue-button dark-revoked reject-team-invite" data-invitetoken="{{ $teamInvite->deny_token }}">Revoke request</button>
        </div>
    </div>
    {{-- @endif --}}
    @endif

    <div class="content">

        <div class="content-header mt-40">
            @if(!$user->ownedTeams->isEmpty())
                <h2>Create a new team!</h2>
            @else
                <h2>It looks like you don't have any teams yet!</h2>
                <p>Fortunately, it's very easy to create one. Let's begin by filling out your teams details.</p>
            @endif
        </div>

        <form id="form-create-team" method="POST" action="{{ route('teams.store') }}" enctype="multipart/form-data" novalidate>
            @csrf

            <div class="group">
                <label for="name">Name your team *</label>
                <input type="text" name="name" value="{{ old('name') }}" id="team-name" class="form-field" placeholder="Enter team name" maxlength="100" autofocus>
            </div>

            <div class="group">
                <label for="url">Enter team URL *</label>
                <input type="text" name="url" value="{{ old('url') }}" id="team-url" placeholder="Enter team URL (Eg. https://url.com)" maxlength="100">
            </div>

            <div class="group">
                <label for="contact">Enter team contact number *</label>
                <input type="text" name="contact" value="{{ old('contact') }}" id="team-contact" placeholder="Team contact number (e.g +243740000000)" maxlength="16">
            </div>

            <div class="group countries">
                <label for="country">Which country are you based in? *</label>
                <div class="country-block-container">
                    <select id="team-country" name="country" value="{{ old('country') }}" autocomplete="off">
                        <option value="">Select country</option>
                        @foreach($countries as $code => $name)
                            <option value="{{ $code }}" @if(old('country') === $code) selected @endif>{{ $name }}</option>
                        @endforeach
                    </select>
                    @svg('chevron-down', '#000')
                </div>
            </div>

            <div class="group">
                <label for="lfile-input">Upload team logo</label>
                <label for="file-input" class="logo-file-container mb-0">
                    <span class="upload-file-name">Upload team logo</span>
                    <input type="file" name="logo_file" class="logo-file" id="logo-file" placeholder="Upload team logo" maxlength="100"  accept="image/*">
                    <button type="button" class="logo-add-icon">@svg('plus', '#fff')</button>
                </label>
                <small class="mb-3">*Max 5MB file size and Max Width of 2000 and Max Height of 2000.</small>
            </div>

            <div class="group">
                <label for="invitations">Invite colleagues or other users</label>
                <input type="email" class="invitation-field" name="invitations" id="invitations" placeholder="Add one email at a time." maxlength="100" autocomplete="off">
                <button class="invite-btn" type="button">INVITE</button>
                <span class="error-email">Valid email required !</span>
                <div class="invite-tags" id="invite-list"></div>
            </div>

            <div class="group">
                <label for="description">Team description</label>
                <textarea name="description" id="description" placeholder="Write a short description about your team" maxlength="512">{{ old('description') }}</textarea>
            </div>

            <div class="form-actions">
                <button class="dark next " id="create">
                    CREATE TEAM @svg('arrow-forward', '#ffffff')
                </button>
            </div>
        </form>

    </div>
@endsection

@push('scripts')
    <script src="{{ mix('/js/templates/teams/create.js') }}"></script>
    <script src="{{ mix('/js/templates/teams/create-update-validation.js') }}"></script>
@endpush
