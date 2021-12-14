(function () {
    function init() {
        var toggleAppEls = document.querySelectorAll('.toggle-app');
        var renewCredentials = document.querySelectorAll('.renew-credentials');
        var environment = document.querySelectorAll('.environment');
        var logNotes = document.querySelectorAll('.log-notes');
        var productStatusButtons = document.querySelectorAll('.product-status-action');
        var appStatusAction = document.querySelectorAll('.app-status-action');

        for (var i = toggleAppEls.length - 1; i >= 0; i--) {
            toggleAppEls[i].addEventListener('click', toggleApp);
        }

        for (var i = renewCredentials.length - 1; i >= 0; i--) {
            renewCredentials[i].addEventListener('submit', confirmRenewCredentials);
        }

        for (var i = environment.length - 1; i >= 0; i--) {
            environment[i].addEventListener('click', switchEnvironment);
        }

        for (var m = 0; m < logNotes.length; m++) {
            logNotes[m].addEventListener('click', viewNote);
        }

        for (var m = 0; m < productStatusButtons.length; m++) {
            productStatusButtons[m].addEventListener('click', showProductNoteDialog);
        }

        for (var i = appStatusAction.length - 1; i >= 0; i--) {
            appStatusAction[i].addEventListener('click', updateAppStatus);
        }
    }

    init();
    ajaxifyOnPopState = updateFilters;
    ajaxifyComplete = init;

    function updateFilters(params) {
        document.getElementById('search-page').value = params['q'] || '';
        document.getElementById('app-filter-status').value = params['app-status'] || '';
        document.getElementById('product-filter-status').value = params['product-status'] || '';
        document.getElementById('filter-country').value = params['countries'] || '';
    }

    function toggleApp() {
        this.closest('.app').classList.toggle('show');
    }

    function confirmRenewCredentials() {
        if (!confirm('Renewing the credentials will revoke the current credentials, do you want to continue?')) return;

        addLoading('Renewing credentials...');
    }

    function switchEnvironment() {
        this.closest('.detail').className = 'detail active-' + this.dataset.environment;
    }

    function viewNote(e) {
        var id = this.dataset.id;
        var noteDialog = document.getElementById('admin-' + id);

        if (!noteDialog) return;

        noteDialog.classList.add('show');
    }

    function showProductNoteDialog() {
        var dialog = document.getElementById('status-dialog');
        var productStatusAction = this;

        dialog.querySelector('.status-dialog-form').addEventListener('submit', function (ev) {
            var product = productStatusAction.parentNode;
            ev.preventDefault();

            document.getElementById('status-dialog').classList.remove('show');

            updateProductStatus({
                action: productStatusAction.dataset.action,
                for: productStatusAction.dataset.for,
                app: product.dataset.aid,
                product: product.dataset.pid,
                displayName: product.dataset.productDisplayName,
                statusNote: this.elements['status-note'].value
            }, product);

            dialog.querySelector('.status-dialog-textarea').value = '';
        }, {
            once: true
        });

        dialog.classList.add('show');
    }

    function updateProductStatus(data, card) {
        var xhr = new XMLHttpRequest();
        var lookup = {
            approve: 'approved',
            revoke: 'revoked'
        };

        addLoading('Updating status...');

        xhr.open('POST', '/admin/apps/' + data.product + '/' + data.action);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.getElementsByName("csrf-token")[0].content);
        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.send(JSON.stringify(data));

        xhr.onload = function () {
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

            removeLoading();

            if (xhr.status === 200) {
                card.className = 'product product-status-' + lookup[data.action];
            } else {
                addAlert('error', result.body || 'There was an error updating the product.');
            }
        };
    }

    function updateAppStatus() {
        var dialog = document.getElementById('status-dialog');
        var dialogForm = dialog.querySelector('.status-dialog-form');

        dialogForm.action = this.dataset.action;
        dialogForm.addEventListener('submit', function () {
            dialog.classList.remove('show');
            addLoading('Updating status...');
        }, {
            once: true
        });

        dialog.querySelector('.app-dialog-status').value = this.dataset.status;
        dialog.classList.add('show');
    }
}());