@push('styles')
    <link rel="stylesheet" href="/css/templates/apps/index.css">
@endpush

@extends('layouts.sidebar')

@section('sidebar')
{{--    @php--}}
{{--        $filters = array('Group'=> $groups,'Categories'=> $productCategories);--}}
{{--    @endphp--}}
    <div class="filter-sidebar">
        <h2>Filter by</h2>
        <h3>Search</h3>

        <input type="text" name="search">

{{--        @foreach ($filters as $filterTitle => $filterGroup)--}}
{{--            <h3>{{$filterTitle}}</h3>--}}
{{--            @foreach ($filterGroup as $filterItem)--}}
{{--                <div class="filter-checkbox">--}}
{{--                    <input type="checkbox" name="{{$filterTitle}}" value="{{$filterItem}}" id="{{$filterTitle}}" onclick="filterProducts('{{$filterTitle}}');"/><label class="filter-label" for="{{$filterTitle}}">{{$filterItem}}</label>--}}
{{--                </div>--}}
{{--            @endforeach--}}
{{--        @endforeach--}}
        <div class="country-filter">
            <h3>Country</h3>
{{--            <x-multiselect id="filter-country" name="filter-country" label="Select country" :options="$countries" />--}}
        </div>

        <button id="clearFilter" class="dark outline" onclick="clearFilter()">Clear filters</button>
    </div>
@endsection

@section('content')

    <x-heading heading="Apps" tags="DASHBOARD">
        <button class="outline dark" id="create">Create new</button>
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
                        <p>Developer email</p>
                    </div>

                    <div class="column">
                        <p>Date created</p>
                    </div>

                    <div class="column">
                        &nbsp;
                    </div>
                </div>
                <div class="body">
                    @forelse($approvedApps as $app)
                        @if(!empty($app['attributes']))
                            <x-app :app="$app" :attr="App\Services\ApigeeService::getAppAttributes($app['attributes'])"></x-app>
                        @endif
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
                        <p>Developer email</p>
                    </div>

                    <div class="column">
                        <p>Date created</p>
                    </div>

                    <div class="column">

                    </div>
                </div>
                <div class="body">
                    @forelse($revokedApps as $app)
                        @if(!empty($app['attributes']))
                            <x-app :app="$app" :attr="App\Services\ApigeeService::getAppAttributes($app['attributes'])"></x-app>
                        @endif
                    @empty
                        <p>No revoked apps.</p>
                    @endforelse
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

        var approveForm = document.querySelectorAll('.app-product-approve');
        for(var m = 0; m < approveForm.length; m++) {
            approveForm[m].addEventListener('submit', handleProductApprove)
        }

        function handleProductApprove(event) {
            event.preventDefault();

            var approveProduct = confirm('Are you sure you want to approve this product?');

            if(approveProduct) {
                //this.submit();
                console.log(event.currentTarget);
            }
        }

        var revokeButtons = document.querySelectorAll('button[class*="product-revoke"]');
        for(var n = 0; n < revokeButtons.length; n++) {
            revokeButtons[n].addEventListener('click', handleProductRevoke)
        }

        function handleProductRevoke(event) {
            event.preventDefault();

            var revokeProduct = confirm('Are you sure you want to approve this product?');

            if(revokeProduct) {
                //this.submit();
                console.log(event.currentTarget);
            }
        }

        var approveAllButtons = document.querySelectorAll('button[class*="dashboard-approve"]');
        for(var o = 0; o < approveAllButtons.length; o++) {
            approveAllButtons[o].addEventListener('click', handleApproveAll)
        }

        function handleApproveAll() {
            console.log(this);
        }

        function revokeAll() {
            console.log(this);
        }

        function complete() {
            console.log(this);
        }
    </script>
@endpush
