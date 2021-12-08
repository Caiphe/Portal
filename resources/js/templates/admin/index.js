(function () {
    var filterForm = document.getElementById('search-form');
    var searchProduct = document.getElementById('search-page');
    var selects = filterForm.querySelectorAll('select');
    var timeout = null;

    searchProduct.addEventListener('input', filter);

    for (var i = selects.length - 1; i >= 0; i--) {
        selects[i].addEventListener('change', submitFilter);
    }

    function filter() {
        if (timeout !== null) {
            clearTimeout(timeout);
            timeout = null;
        }
        timeout = setTimeout(submitFilter, 1000);
    }

    function submitFilter() {
        if (filterForm.requestSubmit !== undefined) {
            filterForm.requestSubmit();
        } else {
            filterForm.submit();
        }
    }
}());