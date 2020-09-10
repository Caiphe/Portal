@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/apps/index.css') }}">
@endpush

@section('title', 'Dashboard')

@section('content')
    
    <div class="container" id="app-index">
        <div class="cols centre-align">
            <h3>Search</h3>
            <input type="text" name="search" id="filter-text" class="filter-text ml-1" placeholder="App or developer name">

            <h3 class="ml-2">Country</h3>
            <x-multiselect id="filter-country" name="filter-country" class="ml-1" label="Select country" :options="$countries->pluck('name', 'code')" />

            <button id="clearFilter" class="dark outline ml-2" onclick="clearFilter()">Clear filters</button>
        </div>

        <div class="row">
            <div class="my-apps">
                <div class="head">
                    <div class="column">
                        <p>App name</p>
                    </div>

                    <div class="column">
                        <p>Countries</p>
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
                    @forelse($apps as $app)
                        @if(!empty($app['attributes']))
                        @php
                            $countryList = $countries->pluck('name', 'code')->toArray();
                            $productCountries = [];
                            if(!is_null($app->country_id)){
                                $productCountries = $countries->where('id', $app->country_id)->pluck('name', 'code')->toArray();
                            } else {
                                $productCountries = $app->products->reduce(function($carry, $product) use ($countryList) {
                                    $locationArray = explode(',', $product->locations);
                                    $carry = array_merge($carry, array_intersect_key($countryList, array_combine($locationArray, $locationArray)));
                                    return $carry;
                                }, []);
                            }
                        @endphp
                        <x-app
                            :app="$app"
                            :attr="$app->attributes"
                            :details="$app->developer"
                            :countries="$productCountries ?: ['all' => 'Global']"
                            type="approved">
                        </x-app>
                        @endif
                    @empty
                        <p>No apps.</p>
                    @endforelse
                    {{ $apps->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.getElementById('filter-text').addEventListener('keyup', filterApps);
        document.getElementById("filter-country").addEventListener('change', filterApps);
        document.getElementById("filter-country-tags").addEventListener('click', filterApps);

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
            modals[l].addEventListener('click', hideMenu)
        }

        function hideMenu() {
            document.querySelector(".modal.show").classList.remove('show');
            document.querySelector(".menu.show").classList.remove('show');
        }

        function filterApps() {
            var apps = document.querySelectorAll('.app');
            var filterText = document.getElementById('filter-text').value;
            var match = new RegExp(filterText, "gi");
            var locations = [];
            var countrySelect = getSelected(
                document.getElementById("filter-country")
            );

            for (var i = apps.length - 1; i >= 0; i--) {
                apps[i].style.display = 'none';

                textValid =
                    filterText === "" || apps[i].dataset.name.match(match) || apps[i].dataset.developer.match(match);

                locations =
                    apps[i].dataset.locations !== undefined
                        ? apps[i].dataset.locations.split(",")
                        : ["all"];

                countriesValid =
                    countrySelect.length === 0 ||
                    locations[0] === "all" ||
                    arrayCompare(locations, countrySelect);

                if (textValid && countriesValid) {
                    apps[i].style.display = 'flex';
                }
            }

            toggleFilter();
        }

        function toggleFilter() {
            var countrySelect = getSelected(document.getElementById("filter-country"));

            var filterText = document.getElementById("filter-text").value;
            if (
                countrySelect.length !== 0 ||
                filterText.length !== 0
            )
                document.getElementById("clearFilter").style.display = "block";
            else if (
                countrySelect.length === 0 ||
                filterText.length === 0
            )
                document.getElementById("clearFilter").style.display = "none";
        }

        function clearFilter() {

            var countrySelect = document.querySelectorAll('#filter-country-tags .tag');

            for (var i = countrySelect.length - 1; i >= 0; i--) {
                countrySelect[i].click();
            }

            document.getElementById("filter-text").value = "";

            filterApps();

            document.getElementById("clearFilter").style.display = "none";
        }

        var productStatusButtons = document.querySelectorAll('button[class*="product-"]');
        for(var m = 0; m < productStatusButtons.length; m++) {
            productStatusButtons[m].addEventListener('click', getProductStatus)
        }

        function getProductStatus(event) {
            var data = {};
            var appProducts = void 0;
            var lookBack = {
                approved: 'approve',
                revoked: 'revoke'
            };

            event.preventDefault();

            if (event.currentTarget.classList.value === 'product-all') {
                hideMenu();

                appProducts = this.parentNode.parentNode.querySelectorAll('.product');

                for (var i = appProducts.length - 1; i >= 0; i--) {
                    if (this.dataset.action === lookBack[appProducts[i].dataset.status]) continue;

                    handleUpdateStatus({
                        action: this.dataset.action,
                        app: appProducts[i].dataset.aid,
                        product: appProducts[i].dataset.pid,
                        displayName: appProducts[i].dataset.productDisplayName
                    }, appProducts[i].querySelector('.status-bar'));
                }
                return;
            }

            handleUpdateStatus({
                action: this.dataset.action,
                app: this.dataset.aid,
                product: this.dataset.pid,
                displayName: this.dataset.productDisplayName
            }, this.parentNode.querySelector('.status-bar'));
        }

        function handleUpdateStatus(data, statusBar) {
            var xhr = new XMLHttpRequest();
            var lookup = {
                approve: 'approved',
                revoke: 'revoked',
                pending: 'pending'
            };

            xhr.open('POST', '/admin/apps/' + data.product + '/' + data.action);
            xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            if(confirm('Are you sure you want to ' + data.action + ' ' + data.displayName + '?')) {
                statusBar.classList.add('loading');

                xhr.send(JSON.stringify(data));
            }

            xhr.onload = function() {
                var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

                if (xhr.status === 200) {
                    statusBar.className = 'status-bar status-' + lookup[data.action];
                } else {
                    statusBar.classList.remove('loading');
                    addAlert('error', result.message || 'There was an error updating the product.');
                }
            };
        }

        function arrayCompare(a, b) {
            var matches = [];
            for (var i = 0; i < a.length; i++) {
                for (var e = 0; e < b.length; e++) {
                    if (a[i] === b[e]) matches.push(a[i]);
                }
            }
            return matches.length > 0;
        }

        function getSelected(multiSelect) {
            var selected = [];
            for (var option of multiSelect.options) {
                if (option.selected) {
                    selected.push(option.value);
                }
            }
            return selected;
        }

        function clearSelected(multiselect) {
            var elements = multiselect.options;
            for (var i = 0; i < elements.length; i++) {
                elements[i].selected = false;
            }
        }
    </script>
@endpush
