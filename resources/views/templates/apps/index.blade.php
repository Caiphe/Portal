@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/apps/index.css') }}">
@endpush

@extends('layouts.sidebar')

@section('sidebar')
    <x-sidebar-accordion id="sidebar-accordion" active="/apps" :list="
    [ 'Manage' =>
        [
            [ 'label' => 'Profile', 'link' => '/profile'],
            [ 'label' => 'My apps', 'link' => '/apps'],
            [ 'label' => 'Teams', 'link' => '/teams'],
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
    Apps
@endsection

@section('content')

    @if(!is_null($ownershipInvite))
    <div class="ownership-request animated">
        @svg('info', '#fff')
        <div class="message-container">
            {{-- The link to be changed to dynamic --}}
            You have been requested to be the new owner of PlusNarrative. Please visit your <a href="/teams/{{ $ownershipInvite->team_id }}/team">team's dashboard</a>
        </div>
        <button class="close-banner">@svg('close', '#fff')</button>
    </div>
    @endif

    <x-heading heading="Apps" tags="DASHBOARD">
        <a href="{{route('app.create')}}" class="button outline dark create-new" id="create"></a>
    </x-heading>

    <x-twofa-warning></x-twofa-warning>

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
        <div class="app-main-container">
            <div class="container" id="app-index">
                <div class="row">
                    <div class="approved-apps">
                        <div class="heading-app">
                            @svg('chevron-down', '#000000')
                            <h3>Approved Apps</h3>
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
                                    <p>Date Created  @svg('arrow-down' ,'#cdcdcd')</p>
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
                                    <p>Date Created  @svg('arrow-down' ,'#cdcdcd')</p>
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
    @endif

@endsection

@push('scripts')
    <script>
        var headings = document.querySelectorAll('.heading-app');

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

        var deleteButtons = document.querySelectorAll('.app-delete');
        for (var m = 0; m < modals.length; m ++) {
            deleteButtons[m].addEventListener('click', handleDeleteMenuClick);
        }

        function handleDeleteMenuClick(event) {
            event.preventDefault();

            var app = event.currentTarget;

            var data = {
                name: app.dataset.name,
                _method: 'DELETE'
            };

            var url = '/apps/' + app.dataset.name;
            var xhr = new XMLHttpRequest();

            if(!confirm('Are you sure you want to delete this app?')) {
                document.querySelector(".menu.show").classList.remove('show');
                return;
            }

            addLoading('Deleting app...');

            xhr.open('POST', url, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');

                xhr.send(JSON.stringify(data));

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        window.location.href = "{{ route('app.index') }}";
                        addAlert('success', 'Application deleted successfully');
                    }
                };

            document.querySelector(".menu.show").classList.remove('show');
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
        OwnershipRequest.classList.remove('hide')

        closeBannerBtn.addEventListener('click', hideOwnershipBanner);
        function hideOwnershipBanner(){
            OwnershipRequest.classList.add('hide');
        }

    </script>
@endpush
