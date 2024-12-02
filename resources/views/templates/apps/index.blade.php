@php
    $user = auth()->user();
@endphp
@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/apps/index.css') }}">
@endpush

@extends('layouts.sidebar')

@section('sidebar')
    <x-sidebar-accordion id="sidebar-accordion" active="/apps" :list="
    [ 'Manage' =>
        [
            [ 'label' => 'My profile', 'link' => '/profile'],
            [ 'label' => 'My apps', 'link' => '/apps'],
            [ 'label' => 'My teams', 'link' => '/teams'],
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
    My apps
@endsection

@section('content')

    @if(!is_null($ownershipInvite))
    <div class="ownership-request animated">
        @svg('info', '#fff')
        <div class="message-container">
            {{-- The link to be changed to dynamic --}}
            You have been requested to be the new owner of {{ $ownershipTeam->name }}. Please visit your <a href="/teams/{{ $ownershipInvite->team_id }}/team">team's dashboard</a>
        </div>
        <button class="close-banner">@svg('close', '#fff')</button>
    </div>
    @endif

    <x-heading heading="My apps" tags="DASHBOARD">
        <a href="{{route('app.create')}}" class="button outline dark create-new" id="create"></a>
    </x-heading>

    <x-twofa-warning></x-twofa-warning>

    @if($teamInvite && !$team->hasUser($user))
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
    {{-- @endif --}}
    @endif

    @if(empty($approvedApps) && empty($revokedApps))
        <div class="container" id="app-empty">
            <div class="row">
                <div class="col-12">
                    @svg('app', '#ffffff')
                    <h1>Looks like you don’t have any apps yet.</h1>
                    <p>Fortunately, it’s very easy to create one.</p>

                    <a href="{{route('app.create')}}" class="button outline dark">Create app</a>
                </div>
            </div>
        </div>
    @else

    <x-dialog-box dialogTitle="App delete" class="">
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

        <div class="app-main-container">
            <div class="container" id="app-index">
                <div class="row">
                    <div class="approved-apps">
                        <div class="heading-app">
                            @svg('chevron-down', '#000000')
                            <h3>Approved apps</h3>
                        </div>

                        <div class="updated-app">
                            <div class="head">
                                <div class="column">
                                    <p>App name  @svg('arrow-down' ,'#cdcdcd')</p>
                                </div>

                                <div class="column">
                                    <p>Callback URL</p>
                                </div>

                                <div class="column">
                                    <p>Country  @svg('arrow-down' ,'#cdcdcd')</p>
                                </div>

                                <div class="column">
                                    <p>Creator  @svg('arrow-down' ,'#cdcdcd')</p>
                                </div>

                                <div class="column">
                                    <p>Date created  @svg('arrow-down' ,'#cdcdcd')</p>
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
                                <div class="column">
                                    <p>App name  @svg('arrow-down' ,'#cdcdcd')</p>
                                </div>

                                <div class="column">
                                    <p>Callback URL</p>
                                </div>

                                <div class="column">
                                    <p>Country  @svg('arrow-down' ,'#cdcdcd')</p>
                                </div>

                                <div class="column">
                                    <p>Creator  @svg('arrow-down' ,'#cdcdcd')</p>
                                </div>

                                <div class="column">
                                    <p>Date created  @svg('arrow-down' ,'#cdcdcd')</p>
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
    @endif

@endsection

@push('scripts')
    <script>
        var headings = document.querySelectorAll('.heading-app');
        var appDeleteShowModalBtn = document.querySelectorAll('.app-delete-modal');
        var modalContainer = document.querySelector('.mdp-dialog-box');

        var cancelBtn = document.querySelector('.cancel-btn');
        if(cancelBtn){
            cancelBtn.addEventListener('click', hideModal);
        }

        function hideModal() {
            modalContainer.classList.remove('show');
        }

        for (var i = 0; i < appDeleteShowModalBtn.length; i++) {
            appDeleteShowModalBtn[i].addEventListener('click', showDeleteAppModal);
        }

        function showDeleteAppModal(){
            modalContainer.classList.add('show');
            document.querySelector('.modal-app-name').innerHTML = this.dataset.displayname;
            document.querySelector('.hidden-app-name').value = this.dataset.appname;
            document.querySelector(".modal.show").classList.remove('show');
            document.querySelector(".menu.show").classList.remove('show');
        }

        for (var i = 0; i < headings.length; i++) {
            headings[i].addEventListener('click', handleHeadingClick)
        }

        function handleHeadingClick(event) {
            var heading = event.currentTarget;

            heading.nextElementSibling.classList.toggle('collapse');
            heading.querySelector('svg').classList.toggle('active');
        }

        var buttons = document.querySelectorAll('.toggle-app');

        for (var j = 0; j < buttons.length; j ++) {
            buttons[j].addEventListener('click', handleButtonClick);
        }

        function handleButtonClick(event) {
            this.parentNode.parentNode.classList.toggle('show')
        }

        var actions = document.querySelectorAll('.actions');

        for (var k = 0; k < actions.length; k ++) {
            actions[k].addEventListener('click', handleMenuClick);
        }

        function handleMenuClick() {
            var parent = this.parentNode.parentNode;

            parent.querySelector('.menu').classList.toggle('show');
            parent.querySelector('.modal').classList.toggle('show');
        }

        var modals = document.querySelectorAll('.modal');
        for (var l = 0; l < modals.length; l ++) {
            modals[l].addEventListener('click', function() {
                document.querySelector(".modal.show").classList.remove('show');
                document.querySelector(".menu.show").classList.remove('show');
            })
        }

        var deleteButtons = document.querySelector('.app-delete');
        if(deleteButtons){
            deleteButtons.addEventListener('click', handleDeleteMenuClick);
        }

        function handleDeleteMenuClick(event) {
            event.preventDefault();

            var app = document.querySelector('.hidden-app-name').value;

            var data = {
                name: app,
                _method: 'DELETE'
            };

            var url = '/apps/' + app;
            var xhr = new XMLHttpRequest();

            addLoading('Deleting app...');

            xhr.open('POST', url, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');

                xhr.send(JSON.stringify(data));

                xhr.onload = function() {
                    removeLoading();

                    if (xhr.status === 200) {
                        addAlert('success', 'Application deleted successfully');
                        setTimeout(reloadTimeOut, 4000);
                    }
                };

        }

        function reloadTimeOut(){
            location.reload();
        }

        document.getElementById('create').addEventListener('click', handleCreateAppClick);

        function handleCreateAppClick() {
            window.location.href = '/apps/create'
        }

        var keys = document.querySelectorAll('.copy');

        for (var i = 0; i < keys.length; i ++) {
            keys[i].addEventListener('click', copyText);
        }

        function copyText() {
            var url = '/apps/' + this.dataset.reference + '/credentials/' + this.dataset.type;
            var xhr = new XMLHttpRequest();
            var btn = this;

            btn.className = 'copy loading';

            xhr.open('GET', url, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.send();

            xhr.onload = function() {
                if(xhr.status === 302 || /login/.test(xhr.responseURL)){
                    addAlert('info', ['You are currently logged out.', 'Refresh the page to login again.']);
                    btn.className = 'copy';
                } else if (xhr.status === 200) {
                    var response = xhr.responseText ? JSON.parse(xhr.responseText) : null;

                    if(response === null){
                        btn.className = 'copy';
                        return void addAlert('error', ['Sorry there was a problem getting the credentials', 'Please try again']);
                    }

                    copyToClipboard(response.credentials);
                    btn.className = 'copy copied';
                }
            };
        }

        function copyToClipboard(text) {
            var dummy = document.createElement("textarea");
            dummy.style.position = 'absolute';
            document.body.appendChild(dummy);
            dummy.value = text;
            dummy.select();
            document.execCommand("copy");
            document.body.removeChild(dummy);
        }

        var closeBannerBtn = document.querySelector('.close-banner');
        var OwnershipRequest = document.querySelector('.ownership-request');
        if(OwnershipRequest){
            OwnershipRequest.classList.remove('hide');

            closeBannerBtn.addEventListener('click', hideOwnershipBanner);
            function hideOwnershipBanner(){
                OwnershipRequest.classList.add('hide');
            }
        }

        // Invits functionality
        var btnAcceptInvite = document.querySelector('.accept-team-invite');
        if(btnAcceptInvite){
            btnAcceptInvite.addEventListener('click', function (event){
                var data = {
                    token: this.dataset.invitetoken,
                };

                handleTimeInvite('/teams/accept', data, event);
            });
        }

        var btnRejectInvite =  document.querySelector('.reject-team-invite')
        if(btnRejectInvite){
            btnRejectInvite.addEventListener('click', function (event){
            var data = {
                token: this.dataset.invitetoken,
            };

            handleTimeInvite('/teams/reject', data, event);
            });
        }

        function handleTimeInvite(url, data, event) {
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
                    addAlert('success', "Thanks, for your response");
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
