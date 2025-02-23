@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/teams/show.css') }}">
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
My team
@endsection

@section('content')
<x-heading heading="My team" tags="Dashboard">
    @if($isAdmin)
        <a href="{{ route('teams.edit', $team->id) }}" class="button dark outline">Edit team profile</a>
    @endif
</x-heading>

<x-dialog-box dialogTitle="Add teammate" class="add-teammate-dialog">
    <form class="form-teammate dialog-custom-form">
        <p class="dialog-text-padding">Invite additional team members or other users</p>
        <div class="form-group-container">
            <input type="text" class="form-control teammate-email" placeholder="Add email to invite users"/>
        </div>
        <div class="radio-container">
            <x-radio-round id="user-radio" name="role_name" value="team_admin">Administrator</x-radio-round>
            <x-radio-round id="user-radio-two" name="role_name" checked value="team_user">User</x-radio-round>
        </div>

        <div class="teammate-error-message">Valid email required!</div>

        <div class="form-team-leave bottom-shadow-container button-container">
            <button type="" class="btn invite-btn primary inactive" data-teamid="{{ $team->id }}">INVITE</button>
            <button type="button" class="btn black-bordered mr-10 close-add-teammate-btn">CANCEL</button>
        </div>
    </form>
</x-dialog-box>
{{-- Add teammate ends --}}


{{-- Transfer ownership Dialog --}}
<x-dialog-box dialogTitle="Transfer Ownership" class="ownweship-modal-container">

    <p class="remove-user-text dialog-text-padding">Which team member would you like to transfer ownership to? </p>

    <div class="scrollable-users-container">
        <ul class="list-users-container">
        @if ($team->users)
            @foreach($team->users as $teamUser)
                @if(!$teamUser->isTeamOwner($team))
                    <li class="each-user">
                        <div class="users-thumbnail" style="background-image: url({{ $teamUser->profile_picture }})"></div>
                        <div class="user-full-name">{{ $teamUser->full_name }}</div>
                        <div class="check-container">
                            <x-radio-round-two name="transfer-ownership-check" id="{{ $teamUser->id }}" value="{{ $teamUser->email }}"></x-radio-round-two>
                        </div>
                    </li>
                @endif
            @endforeach
        @endif
        </ul>
    </div>

    <form class="custom-modal-form bottom-shadow-container button-container mt-40">
        <button type="button" id="transfer-btn" data-teamid="{{ $team->id }}" class="inactive">TRANSFER</button>
        <button type="button" class="btn black-bordered mr-10 ownership-removal-btn">CANCEL</button>
    </form>

</x-dialog-box>
{{-- Transfer ownership ends --}}

{{-- Make Admin Modal Container --}}
<x-dialog-box dialogTitle="Make owner" class="make-admin-modal-container">
    <p class="teammate-text dialog-text-padding">Would you like to make this user a new <strong>owner</strong> of this team?</p>
    <p class="admin-user-name bolder-text dialog-text-padding"></p>
    <form class="custom-modal-form bottom-shadow-container button-container mt-40">
        <button type="button" id="make-owner-btn" class="btn primary admin-removal-btn"  data-teamid="{{ $team->id }}">SUBMIT</button>
        <button type="button" class="btn black-bordered mr-10 make-admin-cancel-btn">CANCEL</button>
    </form>
</x-dialog-box>
{{-- Make admin ends --}}

{{-- Make user modal Container --}}
<x-dialog-box dialogTitle="Make user" class="make-user-modal-container">
    <p class="teammate-text dialog-text-padding">Would you like to make this user a new <strong>owner</strong> of this team?</p>
    <p class="make-user-name bolder-text dialog-text-padding"></p>
    <h2 class="team-head" style="display: none; ">Make User</h2>

    <form class="custom-modal-form bottom-shadow-container button-container mt-40" method="post" id="make-user-form">
        @csrf()
        <input type="hidden" name="team_id" id="each-team-id" value="" />
        <input type="hidden" name="user_id" id="each-user-id" value="" />
        <input type="hidden" name="user_role" id="each-user-role" value="" />

        <button type="button" class="btn primary make-user-btn">SUBMIT</button>
        <button type="button" class="btn black-bordered mr-10 user-admin-cancel-btn">CANCEL</button>
    </form>
</x-dialog-box>
{{-- Make user modal ends --}}

{{-- Delete User Modal --}}
<x-dialog-box dialogTitle="Remove user" class="delete-modal-container">

    <p class="teammate-text dialog-text-padding">Are you sure you want to remove this user?</p>
    <p class="user-name user-delete-name bolder-text dialog-text-padding"></p>

    <form class="custom-modal-form bottom-shadow-container button-container mt-40" method="post">
        @csrf()
        <input type="hidden" value="" name="team_id" class="hidden-team-id"/>
        <input type="hidden" value="" name="team_user_id" class="hidden-team-user-id"/>
        <button type="" class="btn primary remove-user-from-team">REMOVE</button>
        <button type="button" class="btn black-bordered mr-10 cancel-remove-user-btn">CANCEL</button>
    </form>

    <div class="confirm-delete-block">
        <button class="confirm-delete-close-modal">@svg('close-popup', '#000')</button>
        <h2 class="team-head custom-head">Warning</h2>
        <p class="remove-user-text">
            <span class="user-name user-to-delete-name"></span> You are the owner/creator of this team profile. To be able to delete this account, please assign ownership to another user
        </p>

        <div class="scrollable-users-container">
            <ul class="list-users-container">
            @if ($team->users)
                @foreach($team->users as $teamUser)
                    @if(!$teamUser->isTeamOwner($team))
                        <li class="each-user">
                            <div class="users-thumbnail" style="background-image: url({{ $teamUser->profile_picture }})"></div>
                            <div class="user-full-name">{{ $teamUser->full_name }}</div>
                            <div class="check-container">
                                <x-radio-round name="transfer-ownership-check" id="{{ $teamUser->id }}" value="{{ $teamUser->email }}"></x-radio-round>
                            </div>
                        </li>
                    @endif
                @endforeach
            @endif
            </ul>
        </div>

        <form class="form-delete-user ">
            <button type="button" class="btn primary">REMOVE</button>
            <button type="button" class="btn black-bordered mr-10 cancel-removal-btn">CANCEL</button>
        </form>
    </div>
</x-dialog-box>
{{-- Delete User Ends --}}


<div class="mt-2 custom-margin">
    {{-- Top ownerhip block container --}}
    @if($userTeamOwnershipInvite)
    <div class="top-ownership-banner show">
        <div class="message-container">You have been requested to be the owner of this team.</div>
        <div class="btn-block-container">
            {{--  Use the accept endpoint --}}
            <button type="button" class="btn blue-button dark-accept accept-team-ownership" data-user-id="{{ $user->id }}" data-invitetoken="{{ $userTeamOwnershipInvite ? $userTeamOwnershipInvite->accept_token : '' }}">Accept request</button>
            {{--  Use the revoke endpoint --}}
            <button type="button" class="btn blue-button dark-revoked reject-team-ownership" data-user-id="{{ $user->id }}" data-invitetoken="{{ $userTeamOwnershipInvite ? $userTeamOwnershipInvite->deny_token : '' }}">Revoke request</button>
        </div>
    </div>
    @endif

    <div class="header-block team-name-logo">
        {{-- To replace with the team profile picture --}}
        <div class="team-name-logo-container">
            <div class="team-logo"  style="background-image: url({{ $team['logo'] }})"></div>
            <h2 class="team-name">{{ $team->name }} </h2>
        </div>
        <div class="teams-action-button-container">
            @if ($team->users->count() > 1 && $isOwner)
            <button class="btn dark make-owner">Select a new owner</button>
            @endif

            @if($isOwner)
                <button class="btn red-button delete-team-btn">Delete team</button>
            @endif

        </div>
    </div>
       
    <x-dialog-box dialogTitle="Delete team" class="deny-role-modal delete-team-modal">
        <div class="dialog-custom-form">
            
            <p class="dialog-text-padding">Are you sure you want to delete this team?</p>
            <div class="dialog-text-padding team-name-to-delete"><strong>{{ $team->name }}</strong></div>
            <p class="dialog-text-padding">All team members and applications will be removed from the team.</p>
            
            <form class="custom-modal-form bottom-shadow-container button-container mt-40 delete-team-form" method="post" action="{{ route('teams.delete', $team->id) }}">
                @csrf()
                {{-- @method('DELETE') --}}
                <input type="hidden" name="team_id" value="{{ $team->id }}" />
                <input type="hidden" name="team_name" value="{{ $team->name }}" />
                <button type="" class="btn primary submit-team-delete">CONFIRM</button>
                <button type="button" class="btn black-bordered mr-10 cancel-delete-team-btn">CANCEL</button>
            </form>
        </div>
    </x-dialog-box>

    <h5>Team bio</h5>
    <p>{{ $team->description }}</p>
    <div class="detail-left team-detail-left">
        <div class="detail-row cols no-wrap">
            <div class="detail-item"><strong>Contact number</strong></div>
            <div class="detail-item detail-item-description">{{ $team->contact }}</div>
        </div>

        <div class="detail-row cols no-wrap">
            <div class="detail-item"><strong>Team URL</strong></div>
            <div class="detail-item detail-item-description">{{ $team->url }}</div>
        </div>

        <div class="detail-row cols no-wrap">
            <div class="detail-item"><strong>Country</strong></div>
            <div class="detail-item detail-item-description country-name-flag">@svg($country->code, '#000000', 'images/locations')</div>
        </div>
    </div>
    <div class="column">
        <div class="team-members-header">
            <h2>Team membership</h2>

            @if($isAdmin)
                <button class="outline dark add-team-mate-btn">Add a teammate</button>
            @endif

        </div>
    </div>

    <div class="main-team-container">
        <div class="column team-members-list">
            <table class="team-members">
                <tr class="table-title">
                    <td class="bold"><a href="?sort=name&order={{ $order }}">Member name @svg('arrow-down' ,'#cdcdcd')</a></td>
                    <td class="bold bold-role"><a href="?sort=role&order={{ $order }}">Role @svg('arrow-down' ,'#cdcdcd')</a></td>
                    <td class="bold bold-2fa"><a href="?sort=2fa&order={{ $order }}">2FA status @svg('arrow-down' ,'#cdcdcd')</a></td>
                </tr>

                @foreach($team->users as $teamUser)
                    <tr id="team-member-{{ $teamUser->id }}">
                        <td class="member-name-profile">
                            <div class="member-thumbnail"  style="background-image: url({{ $teamUser->profile_picture }})"></div>
                            <p>
                                <strong> {{ $teamUser->full_name }}</strong>
                                ({{ $teamUser->email }})
                            </p>

                            @if($teamUser->isTeamOwner($team))
                                <span class="owner-tag red-tag">OWNER</span>
                            @endif
                        </td>
                        <td id="team-role-{{ $teamUser->id }}">{{ $teamUser->teamRole($team)->label }}</td>
                        <td class="column-container">
                            <span class="twofa-status twofa-{{ strtolower($teamUser->twoFactorStatus()) }}">{{ $teamUser->twoFactorStatus() }}</span>
                            <div class="block-hide-menu"></div>
                            @if($isAdmin && $teamUser->id !== $user->id && $teamUser->id !== $team->owner_id)
                            <button class="btn-actions"></button>
                            {{-- user action menu --}}
                            <div class="block-actions">
                                <ul>
                                    {{---  Uses the transfer endpoint--}}
                                    @if($isOwner)
                                    <li>
                                        <button
                                            class="make-admin make-owner-btn"
                                            data-adminname="{{ $teamUser->full_name }}"
                                            data-invite=""
                                            data-teamid="{{ $team->id }}"
                                            data-userrole = "{{ $teamUser->teamRole($team)->name === 'team_user' ? 'team_admin' : 'team_user' }}"
                                            data-useremail="{{ $teamUser->email }}">
                                            Make owner
                                        </button>
                                    </li>
                                    @endif

                                    {{---  Uses the invite endpoint--}}
                                    <li>
                                        <button
                                            id="change-role-{{ $teamUser->id }}"
                                            class="make-user"
                                            data-username="{{ $teamUser->full_name }}"
                                            data-useremail="{{ $teamUser->email }}"
                                            data-teamid="{{ $team->id }}"
                                            data-teamuserid="{{ $teamUser->id }}"
                                            data-userrole = "{{ $teamUser->teamRole($team)->name === 'team_user' ? 'team_admin' : 'team_user' }}"
                                            >
                                            {{ $teamUser->teamRole($team)->name === 'team_user' ? 'Make administrator' : 'Make user' }}
                                        </button>
                                    </li>

                                    {{---  Uses the leave endpoinst --}}
                                    <li>
                                        <button
                                            class="user-delete"
                                            data-usernamedelete="{{ $teamUser->full_name }}"
                                            data-teamid="{{ $team->id }}"
                                            data-teamuserid="{{ $teamUser->id }}">
                                            Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            @endif
                            {{-- Block end --}}

                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    @if($userTeamOwnershipInvite && $userTeamOwnershipInvite->type === 'ownership' && !$user->isOwnerOfTeam($team))
    {{---  Only show team transfer if team has members --}}
    <div class="transfer-ownership-container" id="transfer-ownership">
        {{-- Transfer ownership container --}}
        <div class="transfer-owner-ship-heading">
            <h2>Transfer ownership</h2>
            {{-- You can add non-active to make--}}
        </div>

        {{-- Transfer request --}}
        <div class="trasfer-container">
            <h4>Transfer requests</h4>
            <div class="site-text">You have been requested to be the new owner of this team. please choose if you would like to accept or revoke the request</div>
            <div class="site-text">You are not the owner of this team, you cannot modify ownership of this team </div>

            <div class="transfer-btn-block">
                {{--  Use the accept endpoint --}}
                <button type="button" class="btn dark dark-accept accept-team-ownership" data-invitetoken="{{ $userTeamOwnershipInvite ? $userTeamOwnershipInvite->accept_token : '' }}">Accept request</button>
                {{--  Use the revoke endpoint --}}
                <button type="button" class="btn dark dark-revoked reject-team-ownership" data-invitetoken="{{ $userTeamOwnershipInvite ? $userTeamOwnershipInvite->deny_token : '' }}">Revoke request</button>
            </div>
        </div>
    </div>
    @elseif ($userTeamOwnershipRequest)
    <div class="transfer-ownership-container" id="transfer-ownership">
        <div class="transfer-owner-ship-heading">
            <h2>Transfer ownership</h2>
        </div>

        <div class="trasfer-container">
            <h4>Transfer requests sent</h4>
            <div class="site-text">A transfer request must be accepted by the team member before the team ownership is confirmed.</div>
        </div>
    </div>
    @endif

    <x-dialog-box dialogTitle="App delete" class="delete-app-modal">
        <p class="dialog-text-padding">Are you sure you want to delete this app?</p>
        <p class="modal-app-name mb-20 boder-text dialog-text-padding"></p>
        <form class="delete bottom-shadow-container button-container">
            @method('DELETE')
            @csrf
            <input type="hidden" value="" name="app-name" class="hidden-app-name"/>
            <button type="button" class="btn primary app-delete">DELETE</button>
            <button type="button" class="btn black-bordered mr-10 cancel-btn">CANCEL</button>
        </form>
    </x-dialog-box>

    <div class="column" id="app-index">
        <div class="row">
            <div class="approved-apps">
                <div class="heading-app">
                    @svg('chevron-down', '#000000')

                    <h3>Approved apps</h3>
                </div>

                <div class="updated-app">
                    <div class="head headings-container">
                        <div class="column-heading">
                            <p>App name {{-- @svg('arrow-down' ,'#cdcdcd') --}}</p>
                        </div>

                        <div class="column-heading">
                            <p>Callback URL</p>
                        </div>

                        <div class="column-heading">
                            <p>Country {{-- @svg('arrow-down' ,'#cdcdcd') --}}</p>
                        </div>

                        <div class="column-heading">
                            <p>Owner {{-- @svg('arrow-down' ,'#cdcdcd') --}}</p>
                        </div>

                        <div class="column-heading">
                            <p>Date created {{-- @svg('arrow-down' ,'#cdcdcd') --}}</p>
                        </div>
                        <div class="column-heading">
                            <p></p>
                        </div>
                    </div>

                    <div class="body app-list-body">
                        @forelse($approvedApps as $app)
                            @if(!empty($app['attributes']))
                                <x-app-list
                                    :app="$app"
                                    :attr="$app['attributes']"
                                    :details="$app['developer']"
                                    :details="$app['developer']"
                                    :countries="!is_null($app->country) ? [$app->country->code => $app->country->name] : ['globe' => 'globe']"
                                    :type="$type = 'approved'">
                                </x-app-list>
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

                    <h3>Revoked apps</h3>
                </div>

                <div class="updated-app">
                    <div class="head">
                        <div class="column-heading">
                            <p>App Name {{-- @svg('arrow-down' ,'#cdcdcd') --}}</p>
                        </div>

                        <div class="column-heading">
                            <p>Callback URL</p>
                        </div>

                        <div class="column-heading">
                            <p>Country {{-- @svg('arrow-down' ,'#cdcdcd') --}}</p>
                        </div>

                        <div class="column-heading">
                            <p>Owner {{-- @svg('arrow-down' ,'#cdcdcd') --}}</p>
                        </div>

                        <div class="column-heading">
                            <p>Date created {{-- @svg('arrow-down' ,'#cdcdcd') --}}</p>
                        </div>
                        <div class="column-heading">
                            <p></p>
                        </div>
                    </div>
                    <div class="body app-list-body">
                        @forelse($revokedApps as $app)
                            @if(!empty($app['attributes']))
                            <x-app-list
                                :app="$app"
                                :attr="$app['attributes']"
                                :details="$app['developer']"
                                :details="$app['developer']"
                                :countries="!is_null($app->country) ? [$app->country->code => $app->country->name] : ['globe' => 'globe']"
                                :type="$type = 'approved'">
                            </x-app-list>
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
