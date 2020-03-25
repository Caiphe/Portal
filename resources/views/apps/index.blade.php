@push('styles')
    <link rel="stylesheet" href="/css/templates/apps/_index.css">
@endpush

@extends('layouts.sidebar')

@section('sidebar')
    Sidebar content
@endsection

@section('content')

    <x-heading heading="Apps" tags="DASHBOARD">
        <button class="outline dark">Create new</button>
    </x-heading>

    <div class="container" id="app-index">
        <div class="row">
            <div>
                <div class="approved-apps">
                    @svg('chevron-down', '#000000')

                    <h3 style="margin-left: 10px">Approved Apps</h3>
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
        </div>

        <div class="row" id="app">
            <div>
                <div class="revoked-apps">
                    @svg('chevron-down', '#000000')

                    <h3 style="margin-left: 10px">Revoked Apps</h3>
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
    </div>

@endsection


@push('scripts')
<script>
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
