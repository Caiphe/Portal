(function () {
    var filterProductsEls = document.querySelectorAll('.filter-products');
    var timeout = null;

    document.getElementById('filter-text').addEventListener('input', debounce);
    document.getElementById('filter-country').addEventListener('change', filterProducts);
    document.getElementById('filter-clear').addEventListener('click', clearFilters);

    for (var i = filterProductsEls.length - 1; i >= 0; i--) {
        filterProductsEls[i].addEventListener('change', filterProducts);
    }

    function debounce() {
        if (timeout) {
            clearTimeout(timeout);
            timeout = null;
        }

        timeout = setTimeout(filterProducts, 512);
    }

    function clearFilters() {
        var categories = document.querySelectorAll('.filter-category:checked');
        var access = document.querySelectorAll('.filter-access:checked');

        document.getElementById('filter-text').value = '';
        document.getElementById('filter-country').value = '';
        document.getElementById('filter-country-tags').innerHTML = '';

        for (var i = categories.length - 1; i >= 0; i--) {
            categories[i].checked = false;
        }

        for (var i = access.length - 1; i >= 0; i--) {
            access[i].checked = false;
        }

        filterProducts();
    }

    function filterProducts() {
        var cards = document.querySelectorAll('.card--product');
        var categoryHeadings = document.querySelectorAll('.category-title');
        var categoryHeadingsShow = [];

        for (var i = cards.length - 1; i >= 0; i--) {
            if (testFilterText(cards[i]) && testCategories(cards[i]) && testAccess(cards[i]) && testLocation(cards[i])) {
                cards[i].style.display = 'inherit';
                categoryHeadingsShow.push(cards[i].dataset.category);
                continue;
            }

            cards[i].style.display = 'none';
        }

        for (var i = categoryHeadings.length - 1; i >= 0; i--) {
            if (categoryHeadingsShow.indexOf(categoryHeadings[i].dataset.category) !== -1) {
                categoryHeadings[i].style.display = 'inherit';
                continue;
            }

            categoryHeadings[i].style.display = 'none';
        }
    }

    function testFilterText(card) {
        var filterText = document.getElementById('filter-text').value.toLowerCase();
        var title = card.dataset.title.toLowerCase();
        var description = card.querySelector('.card__body').textContent.toLowerCase();

        if (filterText === '') return true;
        if (title.indexOf(filterText) !== -1) return true;
        if (description.indexOf(filterText) !== -1) return true;

        return false;
    }

    function testCategories(card) {
        var categories = document.querySelectorAll('.filter-category:checked');

        if (categories.length === 0) return true;

        for (var i = categories.length - 1; i >= 0; i--) {
            if (categories[i].value.indexOf(card.dataset.category) !== -1) return true;
        }

        return false;
    }

    function testAccess(card) {
        var access = document.querySelectorAll('.filter-access:checked');

        if (access.length === 0) return true;

        for (var i = access.length - 1; i >= 0; i--) {
            if (access[i].value.indexOf(card.dataset.access) !== -1) return true;
        }

        return false;
    }

    function testLocation(card) {
        var locations = document.querySelectorAll('#filter-country :checked');
        
        if (locations.length === 0 || card.dataset.locations === undefined) return true;

        for (var i = locations.length - 1; i >= 0; i--) {
            if (card.dataset.locations.split(',').indexOf(locations[i].value) !== -1) return true;
        }

        return false;
    }
}());