@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/dashboard/index.css') }}">
@endpush

@section('title', 'Applications')

@section('page-info')
    <a class="button primary mr-2" href="{{ route('admin.app.create') }}">Create new Application</a>
    <button class="button dark outline" onclick="syncApps();">Sync Apps</button>
@endsection

@section('content')

    <div class="container" id="app-index">
        <div class="approved-app-popup"></div>

        <div class="product-filters">
            <form id="filter-form" class="ajaxify" action="{{ route('admin.dashboard.index') }}" method="GET" data-replace="#table-data">
                <div class="product-filter">
                    <input type="text" name="q" id="filter-text" class="filter-text" placeholder="Search app or developer name" value="{{ $_GET['q'] ?? '' }}">
                </div>

                <div class="product-filter">
                    <select id="app-filter-status" name="app-status">
                        <option @if($appStatus === 'all') selected @endif value="all">All app status</option>
                        <option @if($appStatus === 'approved') selected @endif value="approved">Approved Apps</option>
                        <option @if($appStatus === 'revoked') selected @endif value="revoked">Revoked Apps</option>
                    </select>
                </div>

                <div class="product-filter">
                    <select id="product-filter-status" name="product-status">
                        <option @if($productStatus === 'all') selected @endif value="all">All product status</option>
                        <option @if($productStatus === 'pending') selected @endif value="pending">Pending apps</option>
                        <option @if($productStatus === 'all-approved') selected @endif value="all-approved">All Approved</option>
                        <option @if($productStatus === 'at-least-one-approved') selected @endif value="at-least-one-approved">At Least One Approved</option>
                        <option @if($productStatus === 'all-revoked') selected @endif value="all-revoked">All Revoked</option>
                        <option @if($productStatus === 'at-least-one-revoked') selected @endif value="at-least-one-revoked">At least One Revoked</option>
                    </select>
                </div>

                <div class="product-filter">
                    <select id="filter-country"  name="countries" label="Select country" >
                        <option value="">All countries</option>
                        @foreach($countries as $code => $name)
                            <option value="{{ $code }}" {{ (($selectedCountry === $code) ? 'selected': '') }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>


            </form>
        </div>

        <div id="table-data" class="row">
            @include('templates.admin.dashboard.data', compact('apps', 'countries'))
        </div>
    </div>

    <x-dialog id="status-dialog">
        <form class="status-dialog-form" name="status-note-form" method="POST" action="">
            @csrf
            <h3>Add note:</h3>
            <input class="status-dialog-status" type="hidden" value="approved" name="status">
            <textarea class="status-dialog-textarea" name="status-note" rows="5" placeholder="Optional product status change note" autocomplete="off"></textarea>
            <button class="status-dialog-button">Submit</button>
        </form>
    </x-dialog>
@endsection

@push('scripts')
    <script>
        var timeout = null;

        document.getElementById('filter-text').addEventListener('keyup', filterApps);
        document.getElementById("filter-country").addEventListener('change', submitFilter);
        document.getElementById("app-filter-status").addEventListener('change', submitFilter);
        document.getElementById("product-filter-status").addEventListener('change', submitFilter);

        window.onload = init;
        ajaxifyComplete = init;

        function init() {
            var buttons = document.querySelectorAll('.toggle-app');
            var actions = document.querySelectorAll('.actions');
            var modals = document.querySelectorAll('.modal');
            var productStatusButtons = document.querySelectorAll('.product-status-action');
            var productNotes = document.querySelectorAll('.product-notes');
            var kycStatus = document.querySelectorAll(".kyc-status-select");
            var appStatusUpdate = document.querySelectorAll('.app-status-update');
            var logNotes = document.querySelectorAll('.log-notes');

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

            for(var m = 0; m < productNotes.length; m++) {
                productNotes[m].addEventListener('click', viewNote)
            }

            for (var i = kycStatus.length - 1; i >= 0; i--) {
                kycStatus[i].addEventListener('change', handleKycUpdateStatus);
            }

            for (var i = appStatusUpdate.length - 1; i >= 0; i--) {
                appStatusUpdate[i].addEventListener('click', updateAppStatus);
            }

            for (var i = logNotes.length - 1; i >= 0; i--) {
                logNotes[i].addEventListener('click', viewNote);
            }
        }

        function handleButtonClick() {
            this.parentNode.parentNode.classList.toggle('show')
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
            document.getElementById('filter-form').submit();
        }

        function clearFilter() {

            document.getElementById("filter-text").value = "";
            document.getElementById("filter-country").selectedIndex = 0;
        }

        function getProductStatus(event) {
            var data = {};
            var appProducts = void 0;
            var dialog = document.getElementById('status-dialog');
            var that = this;
            var lookBack = {
                approved: 'approve',
                revoked: 'revoke',
            };

            event.preventDefault();

            if (event.currentTarget.classList.value === 'product-all') {
                hideMenu();

                appProducts = this.parentNode.parentNode.querySelectorAll('.product');

                dialog.querySelector('.status-dialog-form').addEventListener('submit', function(ev){
                    ev.preventDefault();
                    document.getElementById('status-dialog').classList.remove('show');
                    document.querySelector('#status-dialog .status-dialog-textarea').value = '';
                    handleUpdateStatusNoteMany(appProducts, lookBack, this, that);
                }, {
                    once: true
                });

                dialog.classList.add('show');

                return;
            }

            dialog.querySelector('.status-dialog-form').addEventListener('submit', function(ev){
                ev.preventDefault();
                document.getElementById('status-dialog').classList.remove('show');
                handleUpdateStatusNote(this, that);
                document.querySelector('#status-dialog .status-dialog-textarea').value = '';
            }, {
                once: true
            });

            dialog.classList.add('show');
        }

        function handleUpdateStatusNoteMany(appProducts, lookBack, form, el) {
            for (var i = appProducts.length - 1; i >= 0; i--) {
                if (el.dataset.action === lookBack[appProducts[i].dataset.status]) continue;

                handleUpdateStatus({
                    action: el.dataset.action,
                    for: appProducts[i].dataset.for,
                    app: appProducts[i].dataset.aid,
                    product: appProducts[i].dataset.pid,
                    displayName: appProducts[i].dataset.productDisplayName,
                    statusNote: form.elements['status-note'].value
                }, appProducts[i]);
            }
        }

        function handleUpdateStatusNote(form, el) {
            handleUpdateStatus({
                action: el.dataset.action,
                for: el.dataset.for,
                app: el.parentNode.parentNode.dataset.aid,
                product: el.parentNode.parentNode.dataset.pid,
                displayName: el.parentNode.parentNode.dataset.productDisplayName,
                statusNote: form.elements['status-note'].value
            }, el.parentNode);
        }

        function handleUpdateStatus(data, card) {
            var xhr = new XMLHttpRequest();
            var lookup = {
                approve: 'approved',
                revoke: 'revoked'
            };

            addLoading(data.action.replace(/e$/, 'ing') + '...');

            xhr.open('POST', '/admin/apps/' + data.product + '/' + data.action);
            xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.send(JSON.stringify(data));

            xhr.onload = function() {
                var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;
                var approvedAppPopup;

                if (xhr.status === 200) {
                    approvedAppPopup = document.querySelector('.approved-app-popup');

                    approvedAppPopup.innerHTML = '<strong>Product ' + lookup[data.action] + '.</strong> The product <span>' + data.displayName + '</span> has been ' + lookup[data.action];
                    approvedAppPopup.classList.add('show', lookup[data.action]);

                    window.setTimeout(function(){
                        document.querySelector('.approved-app-popup').className = 'approved-app-popup';
                    }, 5000);

                    card.parentNode.className = 'product product-status-' + lookup[data.action];

                } else {
                    addAlert('error', result.body || 'There was an error updating the product.');
                }
                removeLoading();
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

        function updateAppStatus() {
            var dialog = document.getElementById('status-dialog');
            var dialogForm = dialog.querySelector('.status-dialog-form')

            dialogForm.action = this.dataset.action;
            dialogForm.addEventListener('submit', function(){
                dialog.classList.remove('show');
                addLoading('Updating status...');
            });
            dialog.querySelector('.status-dialog-status').value = this.dataset.status;

            hideMenu();
            dialog.classList.add('show');
        }

        function viewNote(e) {
            var id = this.dataset.id;
            var noteDialog = document.getElementById(id + '-note-dialog');

            e.preventDefault();

            if(!noteDialog) return;

            noteDialog.classList.add('show');
        }

        function strSlug(str) {
            return str.replace(/[^a-zA-Z\s]/g, '').replace(/\s+/g, '-').toLowerCase();
        }
    </script>
@endpush
