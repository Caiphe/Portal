(function () {
    function init() {
        var toggleAppEls = document.querySelectorAll('.toggle-app');

        for (var i = toggleAppEls.length - 1; i >= 0; i--) {
            toggleAppEls[i].addEventListener('click', toggleApp)
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
}());