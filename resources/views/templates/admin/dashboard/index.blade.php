@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/apps/index.css') }}">
@endpush

@section('title', 'Applications waiting to be processed')

@section('page-info')
    <button class="button primary" onclick="syncApps();">Sync Apps</button>
@endsection

@section('content')
    
    <div class="container" id="app-index">
        <div class="cols centre-align">
            <form id="filter-form" class="cols centre-align ajaxify" action="{{ route('admin.dashboard.index') }}" method="GET" data-replace="#table-data">
                <h3>Search</h3>
                <input type="text" name="q" id="filter-text" class="filter-text ml-1" placeholder="App or developer name" value="{{ $_GET['q'] ?? '' }}">

                <h3 class="ml-2">Country</h3>
                <x-multiselect id="filter-country" name="countries" class="ml-1" label="Select country" :options="$countries->pluck('name', 'code')" :selected="$_GET['countries'] ?? []" />
            </form>

            <form class="ajaxify" data-replace="#table-data" data-func="clearFilter()" action="{{ route('admin.dashboard.index') }}" method="GET">
                <button id="clearFilter" class="dark outline ml-2">Clear filters</button>
            </form>
        </div>

        <p><sup>*</sup><small>The list below are applications waiting to be processed. If you would like to view a processed application you can use the search form above.</small></p>

        <div id="table-data" class="row">
            @include('templates.admin.dashboard.data', compact('apps', 'countries'))
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        var timeout = null;

        document.getElementById('filter-text').addEventListener('keyup', filterApps);
        document.getElementById("filter-country").addEventListener('change', submitFilter);

        window.onload = init;
        ajaxifyComplete = init;
        
        function init() {
            var buttons = document.querySelectorAll('p.name');
            var actions = document.querySelectorAll('.actions');
            var modals = document.querySelectorAll('.modal');
            var productStatusButtons = document.querySelectorAll('button[class*="product-"]');
            var kycStatus = document.querySelectorAll(".kyc-status-select");

            for (var j = 0; j < buttons.length; j ++) {
                buttons[j].addEventListener('click', handleButtonClick);
            }

            for (var k = 0; k < actions.length; k ++) {
                actions[k].addEventListener('click', handleMenuClick);
            }

            for (var l = 0; l < modals.length; l ++) {
                modals[l].addEventListener('click', hideMenu)
            }

            for(var m = 0; m < productStatusButtons.length; m++) {
                productStatusButtons[m].addEventListener('click', getProductStatus)
            }
            
            for (var i = kycStatus.length - 1; i >= 0; i--) {
                kycStatus[i].addEventListener('change', handleKycUpdateStatus);
            }
        }

        function handleButtonClick(event) {
            var parent = this.parentNode.parentNode;

            if (parent.querySelector('.detail').style.display === 'block') {
                parent.querySelector('.detail').style.display = 'none';
            } else {
                parent.querySelector('.detail').style.display = 'block';
            }
        }

        function handleMenuClick() {
            var parent = this.parentNode.parentNode;

            parent.querySelector('.menu').classList.toggle('show');
            parent.querySelector('.modal').classList.toggle('show');
        }

        function hideMenu() {
            document.querySelector(".modal.show").classList.remove('show');
            document.querySelector(".menu.show").classList.remove('show');
        }

        function filterApps() {
            if(timeout !== null){
                clearTimeout(timeout);
                timeout = null;
            }

            timeout = setTimeout(submitFilter, 1000);
        }

        function submitFilter() {
            document.getElementById('filter-form').requestSubmit();
        }

        function clearFilter() {

            var countrySelect = document.querySelectorAll('#filter-country-tags .tag');

            for (var i = countrySelect.length - 1; i >= 0; i--) {
                countrySelect[i].click();
            }

            document.getElementById("filter-text").value = "";
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
                        for: this.dataset.for,
                        app: appProducts[i].dataset.aid,
                        product: appProducts[i].dataset.pid,
                        displayName: appProducts[i].dataset.productDisplayName
                    }, appProducts[i].querySelector('.status-bar'));
                }
                return;
            }

            handleUpdateStatus({
                action: this.dataset.action,
                for: this.dataset.for,
                app: this.parentNode.dataset.aid,
                product: this.parentNode.dataset.pid,
                displayName: this.parentNode.dataset.productDisplayName
            }, this.parentNode);
        }

        function handleUpdateStatus(data, card) {
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
                card.classList.add('loading');

                xhr.send(JSON.stringify(data));
            }

            xhr.onload = function() {
                var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

                if (xhr.status === 200) {
                    card.className = 'product product-status-' + lookup[data.action];
                } else {
                    card.classList.remove('loading');
                    addAlert('error', result.body || 'There was an error updating the product.');
                }
            };
        }

        function handleKycUpdateStatus() {
            var xhr = new XMLHttpRequest();
            var kycSelect = this;

            xhr.open('POST', '/admin/apps/' + kycSelect.dataset.aid + '/kyc-status');
            xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

           addLoading('Updating KYC status');

            xhr.send(JSON.stringify({
                kyc_status: kycSelect.value
            }));

            xhr.onload = function() {
                var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;
                removeLoading();
                if (xhr.status === 200) {
                    addAlert('success', result.body || 'Success.');
                    kycSelect.parentNode.parentNode.querySelector('.production-products').className = "products production-products kyc-status-" + strSlug(kycSelect.value);
                } else {
                    addAlert('error', result.body || 'There was an error updating the product.');
                }
            };
        }

        function strSlug(str) {
            return str.replace(/[^a-zA-Z\s]/g, '').replace(/\s+/g, '-').toLowerCase();
        }
    </script>
@endpush
