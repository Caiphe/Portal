(function () {
    function init() {
        var toggleAppEls = document.querySelectorAll('.toggle-app');
        var renewCredentials = document.querySelectorAll('.renew-credentials');
        var environment = document.querySelectorAll('.environment');

        for (var i = toggleAppEls.length - 1; i >= 0; i--) {
            toggleAppEls[i].addEventListener('click', toggleApp);
        }

        for (var i = renewCredentials.length - 1; i >= 0; i--) {
            renewCredentials[i].addEventListener('submit', confirmRenewCredentials);
        }

        for (var i = environment.length - 1; i >= 0; i--) {
            environment[i].addEventListener('click', switchEnvironment);
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
}());