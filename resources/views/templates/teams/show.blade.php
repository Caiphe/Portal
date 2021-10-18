@php

    $user = auth()->user();
    $userTeamInvite = $user->getTeamInvite($team);
@endphp
@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/teams/show.css') }}">
@endpush

@extends('layouts.sidebar')

@section('sidebar')
<x-sidebar-accordion id="sidebar-accordion" active="/teams" :list="
    [ 'Manage' =>
        [
            [ 'label' => 'Profile', 'link' => '/profile'],
            [ 'label' => 'My apps', 'link' => '/apps'],
            [ 'label' => 'Teams', 'link' => '/teams']
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
Team
@endsection

@section('content')
<x-heading heading="Team" tags="Dashboard">
    <a href="/profile" class="button dark outline">Edit team profile</a>
</x-heading>

{{-- Edit teammate --}}
<div class="modal-container">
    <div class="overlay-container"></div>
    <div class="add-teammate-block">
        <button class="close-modal">@svg('close-popup', '#000')</button>
        <h2 class="team-head addTeam">Add teammate</h2>
        <p class="teammate-text">Invite additional team members or other users</p>
        <form class="form-teammate">
            <div class="form-group-container">
                <input type="text" class="form-control teammate-email" placeholder="Add email to invite users"/>
                <button type="" class="invite-btn" data-teamid="{{ $team->id }}" data-csrf="{{ csrf_token() }}">INVITE</button>
            </div>
            <div class="radio-container">
                <x-radio-round id="user-radio" name="role_name" value="Administrator">Administrator</x-radio-round>
                <x-radio-round id="user-radio" name="role_name" value="user">User</x-radio-round>
            </div>

            <div class="teamMateErrorMessage">Valid email and User type required !</div>
        </form>
    </div>
</div>
{{-- Edit team mate ends --}}

{{-- Make Admin Modal Container --}}
<div class="make-admin-modal-container">
    <div class="admin-overlay-container"></div>
    <div class="add-teammate-block">
        <button class="admin-close-modal">@svg('close-popup', '#000')</button>
        <h2 class="team-head">Make Admin</h2>
        <p class="teammate-text">Would you like change this user's level of access to <strong>administrator</strong> ?</p>
        <p class="admin-user-name"></p>
        <form class="form-delete-user">
            <button type="button" class="btn primary mr-10 make-admin-cancel-btn">CANCEL</button>
            <button type="button" class="btn dark admin-removal-btn">REMOVE</button>
        </form>
    </div>
</div>
{{-- Make admin ends --}}

{{-- Make user modal Container --}}
<div class="make-user-modal-container">
    <div class="user-overlay-container"></div>
    <div class="add-teammate-block">
        <button class="user-close-modal">@svg('close-popup', '#000')</button>
        <h2 class="team-head">Make User</h2>
        <p class="teammate-text">Would you like change this user's level of access to <strong>user</strong> ?</p>
        <p class="user-name make-user-name"></p>
        <form class="form-delete-user">
            <button type="button" class="btn primary mr-10 user-admin-cancel-btn">CANCEL</button>
            <button type="button" class="btn dark user-remove-btn">REMOVE</button>
        </form>
    </div>
</div>
{{-- Make user modal ends --}}

{{-- Delete User Modal --}}
<div class="delete-modal-container">
    <div class="delete-overlay-container"></div>

    <div class="delete-user-block">
        <button class="delete-close-modal">@svg('close-popup', '#000')</button>

        <h2 class="team-head">Remove User</h2>
        <p class="teammate-text">Are you sure you want to remove this user?</p>
        <p class="user-name user-delete-name"></p>
    {{-- Form to confirm the users removal --}}
        <form class="form-delete-user" method="post">
            @csrf()
            <input type="hidden" value="" name="team_id" class="hidden-team-id"/>
            <input type="hidden" value="" name="team_user_id" class="hidden-team-user-id"/>
            <button type="button" class="btn primary mr-10 cancel-remove-user-btn">CANCEL</button>
            <button type="" class="btn dark remove-user-from-team">REMOVE</button>
        </form>
    </div>

    {{-- This show up if ou are the owner so you should assign a different owner --}}
    <div class="confirm-delete-block">
        <button class="confirm-delete-close-modal">@svg('close-popup', '#000')</button>
        <h2 class="team-head custom-head">Warning</h2>
        <p class="remove-user-text">
            <span class="user-name user-to-delete-name"></span> You are the owner/creator of this team profile. To be able to delete this account, please assign ownership to another user
        </p>

        <div class="scrollable-users-container">
            <ul class="list-users-container">
            @if (!$team->users->isEmpty())
                @foreach($team->users as $user)
                    @if(!$user->isTeamOwner())
                        <li class="each-user">
                            <div class="users-thumbnail" style="background-image: url({{ $user->profile_picture }})"></div>
                            <div class="user-full-name">{{ $user->full_name }}</div>
                            <div class="check-container">
                                <x-radio-round name="transfer-ownership-check" id="{{ $user->id }}" value="{{ $user->email }}"></x-radio-round>
                            </div>
                        </li>
                    @endif
                @endforeach
            @endif
            </ul>
        </div>

        <form class="form-delete-user">
            <button type="button" class="btn primary mr-10 cancel-removal-btn">CANCEL</button>
            <button type="button" class="btn dark">REMOVE</button>
        </form>
    </div>
</div>
{{-- Delete User Ends --}}


{{-- Transfer ownership Modal--}}
<div class="ownweship-modal-container">
    <div class="ownweship-overlay-container"></div>
    {{-- This show up if you are the owner so you should assign a different owner --}}
    <div class="transfer-ownership-block">
        <button class="ownweship-close-modal">@svg('close-popup', '#000')</button>
        <h2 class="team-head custom-head">Transfer Ownership</h2>
        <p class="remove-user-text">Which team member would you like to transfer ownership to? </p>

        <div class="scrollable-users-container">
            <ul class="list-users-container">
            @if (!$team->users->isEmpty())
                @foreach($team->users as $user)
                    @if(!$user->isTeamOwner())
                        <li class="each-user">
                            <div class="users-thumbnail" style="background-image: url({{ $user->profile_picture }})"></div>
                            <div class="user-full-name">{{ $user->full_name }}</div>
                            <div class="check-container">
                                <x-radio-round name="transfer-ownership-check" id="{{ $user->id }}" value="{{ $user->email }}"></x-radio-round>
                            </div>
                        </li>
                    @endif
                @endforeach
            @endif
            </ul>
        </div>

        <form class="form-delete-user mt-40">
            <button type="button" class="btn primary mr-10 ownership-removal-btn">CANCEL</button>
            <button type="button" id="transfer-btn" class="btn dark transfer-btn ">TRANSFER</button>
        </form>

    </div>
</div>

<div class="mt-2">

    {{-- Top ownerhip block container --}}
    <div class="top-ownership-banner @if (!$user->isTeamOwner() && $userTeamInvite) show @endif ">
        <div class="message-container">You have been requested to be the owner of this team.</div>
        <div class="btn-block-container">
            {{--  Use the accept endpoint --}}
            <button type="button" class="btn dark dark-accept" data-invite-token="{{ $userTeamInvite ? $userTeamInvite->accept_token : '' }}">Accept request</button>
            {{--  Use the revoke endpoint --}}
            <button type="button" class="btn dark dark-revoked" data-invite-token="{{ $userTeamInvite ? $userTeamInvite->deny_token : '' }}">Revoke request</button>
        </div>
    </div>
    {{-- @endif --}}

    <div class="header-block team-name-logo">
        {{-- To replace with the team profile picture --}}
        <div class="team-name-logo-container">
            <div class="team-logo"  style="background-image: url({{ $team->logo }})"></div>
            <h2>{{  $team->name }}</h2>
        </div>

        @if ($user->isTeamOwner($team) && $team->users->count() > 1)
            <button class="btn dark make-owner">Select a new owner</button>
        @endif
    </div>

    <h5>Team bio</h5>
    <p>{{ $team->description }}</p>
    <div class="detail-left team-detail-left">
        <div class="detail-row cols no-wrap">
            <div class="detail-item"><strong>Contact number</strong></div>
            <div class="detail-item detail-item-description">{{ $team->contact }}</div>
        </div>

        <div class="detail-row cols no-wrap">
            <div class="detail-item"><strong>Company URL</strong></div>
            <div class="detail-item detail-item-description">{{ $team->url }}</div>
        </div>

        <div class="detail-row cols no-wrap">
            <div class="detail-item"><strong>Country</strong></div>
            <div class="detail-item detail-item-description">{{ $team->country }}</div>
        </div>
    </div>
    <div class="column">
        <div class="team-members-header">
            <h2>Team membership</h2>
            <button class="outline dark add-team-mate-btn">Add a teammate</button>
        </div>
    </div>

    <div class="main-team-container">
        <div class="column team-members-list">
            <table class="team-members">
                <tr class="table-title">
                    <td class="bold">Member name @svg('arrow-down' ,'#cdcdcd')</td>
                    <td class="bold bold-role">Role @svg('arrow-down' ,'#cdcdcd')</td>
                    <td class="bold bold-2fa">2FA Status @svg('arrow-down' ,'#cdcdcd')</td>
                </tr>

                @foreach($team->users as $teamUser)
                    <tr>
                        <td class="member-name-profile">
                            <div class="member-thumbnail"  style="background-image: url({{ $teamUser->profile_picture }})"></div>
                            <p>
                                <strong> {{ $teamUser->first_name }} {{ $teamUser->last_name }}</strong>
                                ({{ $teamUser->email }})
                            </p>

                            @if($teamUser->isTeamOwner())
                                <span class="owner-tag red-tag">OWNER</span>
                            @endif
                        </td>
                        <td>{{ $teamUser->roles()->first()->name  === 'admin' ? 'Administrator' : ucfirst($teamUser->roles()->first()->name) }}</td>
                        <td class="column-container">{{ $teamUser->twoFactorStatus() }}
                            <div class="block-hide-menu"></div>
                            <button class="btn-actions"></button>
                            {{-- user action menu --}}
                            <div class="block-actions">
                                <ul>
                                    @if(!$teamUser->isOwnerOfTeam($team))
                                        {{---  Uses the transfer endpoint--}}
                                        <li>
                                            <button
                                                class="make-admin"
                                                data-adminname="{{ $teamUser->first_name }} {{ $teamUser->last_name }}"
                                                data-invite="">
                                                Make Owner
                                            </button>
                                        </li>
                                    @endif
                                    @if($user->isOwnerOfTeam($team))
                                        {{---  Uses the invite endpoint--}}
                                        <li>
                                            <button
                                                class="make-user"
                                                data-username="{{ $teamUser->first_name }} {{ $teamUser->last_name }}"
                                                data-useremail="{{ $teamUser->email }}"
                                                data-teamid="{{ $team->id }}">
                                                Make User
                                            </button>
                                        </li>
                                    @endif
                                    @if($team->hasUser($user))
                                        {{---  Uses the leave endpoint --}}
                                        <li>
                                            <button
                                                class="user-delete"
                                                data-usernamedelete="{{ $teamUser->first_name }} {{ $teamUser->last_name }}"
                                                data-teamid="{{ $team->id }}"
                                                data-teamuserid="{{ $user->id }}">
                                                Delete
                                            </button>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            {{-- Block end --}}

                        </td>
                    </tr>
                @endforeach

            </table>

            <button class="outline dark add-team-mate-btn-mobile">Add a teammate</button>

        </div>
    </div>

    @if(is_null($team->users))
        {{---  Only show team transfer if team has members --}}
        <div class="transfer-ownership-container" id="transfer-ownership">
            {{-- Transfer ownership container --}}
            <div class="transfer-owner-ship-heading">
                <h2>Transfer ownership</h2>
                {{-- You can add non-active to make--}}
            </div>

            @if($userTeamInvite)
                {{-- Transfer request --}}

                <div class="trasfer-container">
                    <h4>Transfer requests</h4>
                    <div class="site-text">You have been requested to be the new owner of this team. please choose if you would like to accept or revoke the request</div>
                    <div class="site-text">You are not the owner of this team, you cannot modify ownership of this team </div>

                    <div class="transfer-btn-block">
                        {{--  Use the accept endpoint --}}
                        <button type="button" class="btn dark dark-accept" data-invite-token="{{ $userTeamInvite ? $userTeamInvite->accept_token : '' }}">Accept request</button>
                        {{--  Use the revoke endpoint --}}
                        <button type="button" class="btn dark dark-revoked" data-invite-token="{{ $userTeamInvite ? $userTeamInvite->deny_token : '' }}">Revoke request</button>
                    </div>
                </div>
            @endif

        </div>
    @endif

    <div class="column" id="app-index">
        <div class="row">
            <div class="approved-apps">
                <div class="heading-app">
                    @svg('chevron-down', '#000000')

                    <h3>Approved Apps</h3>
                </div>

                <div class="updated-app">
                    <div class="head headings-container">
                        <div class="column-heading">
                            <p>App Name @svg('arrow-down' ,'#cdcdcd')</p>
                        </div>

                        <div class="column-heading">
                            <p>Callback URL</p>
                        </div>

                        <div class="column-heading">
                            <p>Country @svg('arrow-down' ,'#cdcdcd')</p>
                        </div>

                        <div class="column-heading">
                            <p>Creator @svg('arrow-down' ,'#cdcdcd')</p>
                        </div>

                        <div class="column-heading">
                            <p>Date created @svg('arrow-down' ,'#cdcdcd')</p>
                        </div>
                        <div class="column-heading">
                            <p></p>
                        </div>
                    </div>

                    <div class="body app-updated-body">
                        @forelse($approvedApps as $app)
                            @if(!empty($app['attributes']))
                                <x-app-updated
                                    :app="$app"
                                    :attr="$app['attributes']"
                                    :details="$app['developer']"
                                    :details="$app['developer']"
                                    :countries="!is_null($app->country) ? [$app->country->code => $app->country->name] : ['globe' => 'globe']"
                                    :type="$type = 'approved'">
                                </x-app-updated>
                            @endif
                        @empty
                            <p>No approved apps.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="app">
            <div class="revoked-apps">
                <div class="heading-app">
                    @svg('chevron-down', '#000000')

                    <h3>Revoked Apps</h3>
                </div>

                <div class="updated-app">
                    <div class="head">
                        <div class="column-heading">
                            <p>App Name @svg('arrow-down' ,'#cdcdcd')</p>
                        </div>

                        <div class="column-heading">
                            <p>Callback URL</p>
                        </div>

                        <div class="column-heading">
                            <p>Country @svg('arrow-down' ,'#cdcdcd')</p>
                        </div>

                        <div class="column-heading">
                            <p>Creator @svg('arrow-down' ,'#cdcdcd')</p>
                        </div>

                        <div class="column-heading">
                            <p>Date created @svg('arrow-down' ,'#cdcdcd')</p>
                        </div>
                        <div class="column-heading">
                            <p></p>
                        </div>
                    </div>
                    <div class="body app-updated-body">
                        @forelse($revokedApps as $app)
                            @if(!empty($app['attributes']))
                            <x-app-updated
                                :app="$app"
                                :attr="$app['attributes']"
                                :details="$app['developer']"
                                :details="$app['developer']"
                                :countries="!is_null($app->country) ? [$app->country->code => $app->country->name] : ['globe' => 'globe']"
                                :type="$type = 'approved'">
                            </x-app-updated>
                            @endif
                        @empty
                            <p>No revoked apps.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ mix('/js/templates/teams/show.js') }}"></script>
@endpush
