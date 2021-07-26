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

    <x-heading heading="Apps" tags="DASHBOARD">
        <a href="{{route('app.create')}}" class="button outline dark" id="create">Create new</a>
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
        <div class="container" id="app-index">
            <div class="row">
                <div class="approved-apps">
                    <div class="heading-app">
                        @svg('chevron-down', '#000000')

                        <h3>Approved Apps</h3>
                    </div>

                    <div class="my-apps">
                        <div class="head">
                            <div class="column">
                                <p>App name</p>
                            </div>

                            <div class="column">
                                <p>Region</p>
                            </div>

                            <div class="column">
                                <p>Callback URL</p>
                            </div>

                            <div class="column">
                                <p>Last updated</p>
                            </div>

                            <div class="column">
                                &nbsp;
                            </div>
                        </div>
                        <div class="body">
                            @forelse($approvedApps as $app)
                                @if(!empty($app['attributes']))
                                <x-app
                                    :app="$app"
                                    :attr="$app['attributes']"
                                    :details="$app['developer']"
                                    :countries="!is_null($app->country) ? [$app->country->code => $app->country->name] : ['globe' => 'globe']"
                                    :type="$type = 'approved'">
                                </x-app>
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

                    <div class="my-apps">
                        <div class="head">
                            <div class="column">
                                <p>App name</p>
                            </div>

                            <div class="column">
                                <p>Reason</p>
                            </div>

                            <div class="column">
                                <p>Callback URL</p>
                            </div>

                            <div class="column">
                                <p>Date updated</p>
                            </div>

                            <div class="column">

                            </div>
                        </div>
                        <div class="body">
                            @forelse($revokedApps as $app)
                                @if(!empty($app['attributes']))
                                    <x-app
                                        :app="$app"
                                        :attr="$app['attributes']"
                                        :details="$app['developer']"
                                        :countries="!is_null($app->country) ? [$app->country->code => $app->country->name] : ['globe' => 'globe']"
                                        :type="$type = 'revoked'">
                                    </x-app>
                                @endif
                            @empty
                                <p>No revoked apps.</p>
                            @endforelse
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

        var buttons = document.querySelectorAll('.name');

        for (var j = 0; j < buttons.length; j ++) {
            buttons[j].addEventListener('click', handleButtonClick);
        }

        function handleButtonClick(event) {
            var parent = this.parentNode.parentNode;

            if (parent.querySelector('.detail').style.display === 'block') {
                parent.querySelector('.detail').style.display = 'none';
            } else {
                parent.querySelector('.detail').style.display = 'block';
            }
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

    </script>
@endpush
