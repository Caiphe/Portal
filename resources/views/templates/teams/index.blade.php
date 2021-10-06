@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/teams/show.css') }}">
@endpush

@extends('layouts.sidebar')

@section('sidebar')
    <x-sidebar-accordion id="sidebar-accordion" active="/teams/create" :list="
    [ 'Manage' =>
        [
            [ 'label' => 'Profile', 'link' => '/profile'],
            [ 'label' => 'My apps', 'link' => '/apps'],
            [ 'label' => 'Teams', 'link' => '/teams/create']
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
        <a href="{{ route('teams.create') }}" class="button dark outline">Creare New</a>
    </x-heading>

    <div class="modal-container">
        {{-- leave the copany pop up --}}
        <div class="overlay-container"></div>
        <div class="add-teammate-block">
            <button class="close-modal">@svg('close-popup', '#000')</button>

            <h2 class="team-head">Leave team</h2>
            <p class="teammate-text">Are you sure you want to leave this company?</p>
            <p class="app-name team-name"></p>
            <form class="form-team">
                <button type="button" class="btn primary mr-10 cancel-btn">CANCEL</button>
                <button type="" class="btn dark">LEAVE</button>
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
                        <td class="bold">Members @svg('arrow-down' ,'#cdcdcd')</td>
                        <td class="bold">Apps @svg('arrow-down' ,'#cdcdcd')</td>
                        <td>&nbsp;</td>
                    </tr>
                    @foreach($teams as $team)
                        <tr class="team-app-list">
                            <td class="company-logo-name">
                                <div class="company-logo " style="background-image: url('/images/user-thumbnail.jpg')"></div>
                                <a class="company-name-a bold" href="{{route('team.show', [ 'id' => $team['id'] ])}}">{{ $team['name'] }}</a>
                            </td>
                            <td>{{ $team['country'] }}</td>
                            <td>{{ $team['members'] }}</td>
                            <td>{{ $team['apps_count'] }}</td>
                            <td>
                                <button type="button" class="button red-button leave-team" data-teamid="{{ $team['id'] }}" data-teamname="{{ $team['name'] }}" data-teammember="{{ auth()->user()->developer_id }}" data-teamhandler="{{ route('teams.leave.team') }}">LEAVE</button>
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

        for (var i = 0; i < leaveTeamBtn.length; i++) {
            leaveTeamBtn[i].addEventListener('click', function(){
                modalContainer.classList.add('show');

                var teamId  = this.dataset.teamid;
                var teamName  = this.dataset.teamname;
                var teamUser  = this.dataset.teammember;

                teamNameText.innerHTML = teamName;

                var data = {
                    team_id: teamId,
                    name: teamName,
                    member: teamUser
                };

                handleLeaveTeamAction(this.dataset.teamhandler, data);
            });
        }

        clodeModal.addEventListener('click', hideModal);
        cancelBtn.addEventListener('click', hideModal)
        overlayContainer.addEventListener('click', hideModal)

        function hideModal() {
            modalContainer.classList.remove('show');
        }

        /**
         * Handle leaving of Team by a Member
         *
         * @param url
         * @param data
         */
        function handleLeaveTeamAction( url, data ) {

            var xhr = new XMLHttpRequest();

            xhr.open('POST', url, true);

            xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.send(JSON.stringify(data));

            xhr.onload = function() {
                if (xhr.status === 200 && this.response.success === true) {
                    addAlert('success', this.response.message);
                } else {
                    addAlert('error',  this.response.message);
                }
            };

            hideModal();
        }
    </script>
@endpush
