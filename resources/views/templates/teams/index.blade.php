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
            <button class="close-modal">@svg('close', '#000')</button>
    
            <h2 class="team-head">Leave team</h2>
            <p class="teammate-text">Are you sure you want to leave this company?</p>
            <p class="app-name">Plusnarrative</p>
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
                                <a class="company-name-a bold" href="{{route('team.show', [ 'id' => $team->id ])}}">{{ $team->name }}</a>
                            </td>
                            <td>{{ $team->country }}</td>
                            <td>2 of 5</td>
                            <td>7</td>
                            <td>
                                <button type="button" class="button red-button leave-team" data-teamname="{{ $team->name }}">LEAVE</button>
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

        for (var i = 0; i < leaveTeamBtn.length; i++){
            leaveTeamBtn[i].addEventListener('click', function(){
                modalContainer.classList.add('show');
                var teamName  = this.getAttribute("data-teamname");
                console.log(teamName);
            });
        }

        clodeModal.addEventListener('click', hideModal);
        cancelBtn.addEventListener('click', hideModal)
        overlayContainer.addEventListener('click', hideModal)

        function hideModal(){
            modalContainer.classList.remove('show');
        }
    </script>
@endpush
