@push('styles')
    <link rel="stylesheet" href="/css/templates/apps/_index.css">
@endpush

@extends('layouts.sidebar')

@section('sidebar')
    <x-sidebar-accordion id="sidebar-accordion"
                         :list="
    [ 'Manage' =>
        [
            [ 'label' => 'Profile', 'link' => '#'],
            [ 'label' => 'Approved apps', 'link' => '#'],
            [ 'label' => 'Revoked apps','link' => '#'],
        ],
        'Discover' =>
        [
            [ 'label' => 'Browse all products', 'link' => '#'],
            [ 'label' => 'Working with our products','link' => '#'],
        ]
    ]
    " />
@endsection

@section('content')

    <x-heading heading="Apps" tags="DASHBOARD">
        <button class="outline dark">Create new</button>
    </x-heading>

    @if(empty($approved_apps) && empty($revoked_apps))
        <div class="container" id="app-empty">
            <div class="row">
                <div class="col-12">
                    @svg('app', '#ffffff')
                    <h1>Looks like you don’t have any apps yet.</h1>
                    <p>Fortunately, it’s very easy to create one.</p>

                    <button class="outline dark">Create app</button>
                </div>
            </div>
        </div>
    @else
    <div class="container" id="app-index">
        <div class="row">
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
                        <p>Regions</p>
                    </div>

                    <div class="column">
                        <p>Callback URL</p>
                    </div>

                    <div class="column">
                        <p>Date created</p>
                    </div>

                    <div class="column">
                        &nbsp;
                    </div>
                </div>
                <div class="body">
                    @forelse($approved_apps as $app)
                        <x-app :name="$app['attributes'][1]['value']"></x-app>
                    @empty
                        <p>No approved apps.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="row" id="app">
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
                        <p>Date created</p>
                    </div>

                    <div class="column">

                    </div>
                </div>
                <div class="body">
                    @forelse($revoked_apps as $app)
                        <x-app :name="$app['name']"></x-app>
                    @empty
                        <p>No revoked apps.</p>
                    @endforelse
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

    function handleHeadingClick() {
        if (this.nextElementSibling.style.display === 'none' || this.nextElementSibling.style.display === '') {
            this.nextElementSibling.style.display = 'block';
            this.querySelector('svg').classList.remove('active');
        } else {
            this.nextElementSibling.style.display = 'none';
            this.querySelector('svg').classList.add('active');
        }
    }

    var buttons = document.querySelectorAll('.name');

    for (var i = 0; i < buttons.length; i ++) {
        buttons[i].addEventListener('click', handleButtonClick);
    }

    function handleButtonClick() {
        var parent = this.parentNode.parentNode;

        if (parent.querySelector('.detail').style.display === 'block') {
            parent.querySelector('.detail').style.display = 'none';
        } else {
            parent.querySelector('.detail').style.display = 'block';
        }
    }

    var actions = document.querySelectorAll('.actions');

    for (var i = 0; i < actions.length; i ++) {
        actions[i].addEventListener('click', handleMenuClick);
    }

    function handleMenuClick(event) {
        var parent = this.parentNode.parentNode;

        var isClickInside = parent.contains(event.target);

        if (isClickInside && parent.lastElementChild.style.display === 'block') {
            parent.lastElementChild.style.display = 'none';
        } else {
            parent.lastElementChild.style.display = 'block';
        }
    }

    var keys = document.querySelectorAll('.copy');

    for (var i = 0; i < keys.length; i ++) {
        keys[i].addEventListener('click', copyText);
    }

    function copyText(id) {
        var el = document.getElementById(id);
        el.select();
        /* Copy the text inside the text field */
        document.execCommand("copy");
        el.blur();
    }
</script>
@endpush
