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

    <div class="modal-container">
        {{-- leave the copany pop up --}}
        <div class="overlay-container"></div>
        <div class="add-teammate-block">
            <button class="close-modal">@svg('close-popup', '#000')</button>

            <h2 class="team-head">Leave team</h2>
            <p class="teammate-text">Are you sure you want to leave this team?</p>
            <p class="app-name team-name mb-20"></p>
            <form class="form-team-leave">
                <input type="hidden" value="" name="team_id" class="hidden-team-id"/>
                <input type="hidden" value="" name="team_user_id" class="hidden-team-user-id"/>
                <button type="button" class="btn primary mr-10 cancel-btn">CANCEL</button>
                <button type="button" class="btn dark leave-team-btn">LEAVE</button>
            </form>
        </div>
    </div>


    <div class="team-block-container">
        <div class="mt-2">
            <div class="column">
                <table class="teams">
                    <tr class="table-title">
                        <td class="bold">Team Name @svg('arrow-down' ,'#cdcdcd')</td>
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
                                <button
                                    type="button"
                                    class="button red-button leave-team"
                                    data-teamname="{{ $team->name }}"
                                    data-teamid="{{ $team->id }}"
                                    data-teamuser="{{ $user->id }}">
                                    LEAVE
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        var clodeModal = document.querySelector('.close-modal');
        var modalContainer = document.querySelector('.modal-container');
        var leaveTeamBtn = document.querySelectorAll('.leave-team');
        var overlayContainer = document.querySelector('.overlay-container');
        var cancelBtn = document.querySelector('.cancel-btn');
        var teamNameText = document.querySelector('.team-name');
        var leaveTeamForm = document.getElementById('form-team-leave');
        var leaveTeamActionBtn = document.querySelector('.leave-team-btn');
        var hiddenTeamId = document.querySelector('.hidden-team-id');
        var hiddenTeamUserId = document.querySelector('.hidden-team-user-id');


        for (var i = 0; i < leaveTeamBtn.length; i++) {
            leaveTeamBtn[i].addEventListener('click', showLeaveTeamModal);
        }

        clodeModal.addEventListener('click', hideModal);
        cancelBtn.addEventListener('click', hideModal)
        overlayContainer.addEventListener('click', hideModal)

        function showLeaveTeamModal(){
            modalContainer.classList.add('show');

            teamNameText.innerHTML = this.dataset.teamname;

            //Hidden fields to track the Team being left
            hiddenTeamId.value = this.dataset.teamid
            hiddenTeamUserId.value = this.dataset.teamuser
        }

        function hideModal() {
            modalContainer.classList.remove('show');
        }

        leaveTeamActionBtn.addEventListener('click', function(event){
            var xhr = new XMLHttpRequest();
            var data = {
                team_id: hiddenTeamId.value,
                user_id: hiddenTeamUserId.value
            }
            var url = "teams/" + data.team_id + "/leave";

            event.preventDefault();

            addLoading('Leaving...');

            xhr.open('POST', url);
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader("X-CSRF-TOKEN",
                document.getElementsByName("csrf-token")[0].content
            );

            xhr.send(JSON.stringify(data));

            xhr.onload = function() {
                if (xhr.status === 200) {

                    modalContainer.classList.remove('show');

                    addAlert('success', ['Team successfully left.', 'You will be redirected to your teams page shortly.'], function(){
                        window.location.href = "{{ route('teams.listing') }}";
                    });
                } else {
                    var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

                    if(result.errors) {
                        result.message = [];
                        for(var error in result.errors){
                            result.message.push(result.errors[error]);
                        }
                    }

                    addAlert('error', result.message || 'Sorry there was a problem leaving team. Please try again.');
                }

                removeLoading();
            };

        });


        var btnAcceptInvite = document.querySelector('.accept-team-invite');
        if(btnAcceptInvite){

            btnAcceptInvite.addEventListener('click', function (event){
                var data = {
                    token: this.dataset.invitetoken,
                };

                handleInvite('/teams/accept', data, event);
            });
        }

        var btnRejectInvite =  document.querySelector('.reject-team-invite')
        if(btnRejectInvite){
            btnRejectInvite.addEventListener('click', function (event){
                var data = {
                    token: this.dataset.invitetoken,
                };

                handleInvite('/teams/reject', data, event);
            });
        }


        function handleInvite(url, data, event) {
            var xhr = new XMLHttpRequest();

            event.preventDefault();

            xhr.open('POST', url);
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader(
                "X-CSRF-TOKEN",
                document.getElementsByName("csrf-token")[0].content
            );

            xhr.send(JSON.stringify(data));

            addLoading('Handling team invite response.');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.querySelector('.top-invite-banner').classList.remove('show');
                    if(/reject/.test(url)){
                        addAlert('success', "Thanks, for your response");
                    } else {
                       addAlert('success', ["Thanks, for your response", "The page will refresh shortly"], function(){
                            window.location.reload();
                        });
                    }
                } else {
                    var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

                    if(result.errors) {
                        result.message = [];
                        for(var error in result.errors){
                            result.message.push(result.errors[error]);
                        }
                    }

                    addAlert('error', result.message || 'Sorry there was a problem handling team invitation. Please try again.');
                }

                removeLoading();
            };
        }
    </script>
@endpush
