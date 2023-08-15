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
    My teams
@endsection

@section('content')
    <x-heading heading="My teams" tags="Dashboard">
        <a href="{{ route('teams.create') }}" class="button dark outline">Create new</a>
    </x-heading>

    {{-- && !$team->hasUser($user) --}}
    @if ($teamInvite && $team && !$team->hasUser($user))
    {{-- Top ownerhip block container --}}
    <div class="top-invite-banner show">
        <div class="message-container">You have been requested to be part of {{ $team->name }}.</div>
        <div class="btn-block-container">
            {{--  Use the accept endpoint --}}
            <button type="button" class="btn blue-button dark-accept accept-team-invite" data-invitetoken="{{ $teamInvite->accept_token }}">Accept request</button>
            {{--  Use the revoke endpoint --}}
            <button type="button" class="btn blue-button dark-revoked reject-team-invite" data-invitetoken="{{ $teamInvite->deny_token }}">Revoke request</button>
        </div>
    </div>
    @endif

    <x-dialog-box dialogTitle="Leave team" class="">
        <p class="dialog-text-padding">Are you sure you want to leave this team?</p>
        <p class="app-name team-name mb-20 boder-text dialog-text-padding"></p>
        <form class="form-team-leave bottom-shadow-container button-container">
            <input type="hidden" value="" name="team_id" class="hidden-team-id"/>
            <input type="hidden" value="" name="team_user_id" class="hidden-team-user-id"/>
            <button type="button" class="btn primary leave-team-btn">LEAVE</button>
            <button type="button" class="btn black-bordered mr-10 cancel-btn">CANCEL</button>
        </form>
    </x-dialog-box>

    <div class="team-block-container">
        <div class="mt-2">
            <div class="column">
                <table class="teams">
                    <tr class="table-title">
                        <td class="bold">Team name @svg('arrow-down' ,'#cdcdcd')</td>
                        <td class="bold">Country @svg('arrow-down' ,'#cdcdcd')</td>
                        <td class="bold members-column">Members @svg('arrow-down' ,'#cdcdcd')</td>
                        <td class="bold apps-column">Apps @svg('arrow-down' ,'#cdcdcd')</td>
                        <td>&nbsp;</td>
                    </tr>
                    @foreach($teams as $team)

                    <tr class="team-app-list">
                        <td class="company-logo-name word-wrap-text">
                            <div class="company-logo" style="background-image: url({{ $team->logo }})"></div>
                            <a class="company-name-a bold" href="{{route('team.show', [ 'id' => $team->id ])}}">{{ $team->name }}</a>
                        </td>
                        <td>{{ $team->teamCountry->name }}</td>
                        <td>{{ $team->users_count }}</td>
                        <td>{{ $team->apps_count }}</td>
                        <td>
                            @if($team->users->count() > 1 && auth()->user()->isTeamOwner($team))
                            <x-dialog-box dialogTitle="Transfer Ownership" class="ownweship-modal-container">

                                <p class="remove-user-text dialog-text-padding">Which team member would you like to transfer ownership to before you leave this team? </p>

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

                                <form class="custom-modal-form bottom-shadow-container button-container mt-40" action="{{ route('teams.leave.make.owner', $team->id) }}">
                                    @csrf
                                    <input type="hidden" value="{{ $team->id }}" name="team_id" />
                                    <button type="button" id="transfer-btn" data-teamid="{{ $team->id }}" class="transfer-btn inactive">TRANSFER</button>
                                    <button type="button" class="btn black-bordered mr-10 cancel-transfer">CANCEL</button>
                                </form>
                            </x-dialog-box>

                            <button
                                type="button"
                                class="button red-button leave-team-transfer"
                                data-teamname="{{ $team->name }}"
                                data-teamid="{{ $team->id }}"
                                data-teamuser="{{ $user->id }}">
                                LEAVE
                            </button>
                            @else


                            <button
                                type="button"
                                class="button red-button leave-team"
                                data-teamname="{{ $team->name }}"
                                data-teamid="{{ $team->id }}"
                                data-teamuser="{{ $user->id }}">
                                LEAVE
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection

@once
@push('scripts')
<script src="{{ mix('/js/templates/teams/index.js') }}"></script>
@endpush
@endonce
