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
                        <p>Status</p>
                    </div>

                    <div class="column">
                        &nbsp;
                    </div>
                </div>
                <div class="body">
                    @foreach($approved_apps as $app)
                        <x-app :name="$app['name']"></x-app>
                    @endforeach
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
                        <p>Status</p>
                    </div>

                    <div class="column">
                        &nbsp;
                    </div>
                </div>
                <div class="body">
                    @foreach($revoked_apps as $app)
                        <x-app :name="$app['name']"></x-app>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection


@push('scripts')
<script>
    var headings = document.querySelectorAll('.heading-app');

    for (var i = 0; i < headings.length; i++) {
        headings[i].addEventListener('click', handleHeadingClick)
    }

    function handleHeadingClick() {
        if (this.nextElementSibling.style.display === 'block') {
            this.nextElementSibling.style.display = 'none';
            console.log(this.querySelector('svg'));
        } else {
            this.nextElementSibling.style.display = 'block';
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
        keys[i].addEventListener('click', handleCopyClick);
    }

    function handleCopyClick() {

    }
</script>
@endpush
