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
                        <p>&nbsp;</p>
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
                        <p>&nbsp;</p>
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
        var parent = this;

        console.log(parent.nextSibling);

        if (parent.lastElementChild.style.display === 'block') {
            parent.lastElementChild.style.display = 'none';
        } else {
            parent.lastElementChild.style.display = 'block';
        }
    }


    var buttons = document.querySelectorAll('.name');

    for (var i = 0; i < buttons.length; i ++) {
        buttons[i].addEventListener('click', handleButtonClick);
    }

    function handleButtonClick() {
        var parent = this.parentNode.parentNode;

        if (parent.lastElementChild.style.display === 'block') {
            parent.lastElementChild.style.display = 'none';
        } else {
            parent.lastElementChild.style.display = 'block';
        }
    }

    var actions = document.querySelectorAll('.actions');

    for (var i = 0; i < actions.length; i ++) {
        actions[i].addEventListener('click', handleMenuClick);
    }

    function handleMenuClick() {
        var menu = document.querySelector('.menu');

        menu.style.display = 'block';
    }
</script>
@endpush
