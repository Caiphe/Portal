(function () {
    var filterProductsEls = document.querySelectorAll('.filter-products');
    var timeout = null;

    var currentCategory = localStorage.getItem("category");
    if(currentCategory){
        var categoriesChecks = document.querySelectorAll('.filter-category');

        categoriesChecks.forEach(function(checkbox) {
            if (!checkbox.checked && checkbox.nextElementSibling.innerHTML === currentCategory)  {
                checkbox.checked = true;
                filterProducts();
            }
        });
    }

    document.getElementById('filter-text').addEventListener('input', debounce);
    document.getElementById('filter-group').addEventListener('change', filterProducts);
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
        var countries = document.querySelectorAll('.filter-country:checked');
        var access = document.querySelectorAll('.filter-access:checked');

        document.getElementById('filter-text').value = '';
        document.getElementById('filter-group').value = '';
        document.getElementById('filter-group-tags').innerHTML = '';

        for (var i = categories.length - 1; i >= 0; i--) {
            categories[i].checked = false;
        }

        for (var i = countries.length - 1; i >= 0; i--) {
            countries[i].checked = false;
        }

        for (var i = access.length - 1; i >= 0; i--) {
            access[i].checked = false;
        }

        filterProducts();
        removeFilteredCount();
    }

    // Clear category
    document.querySelector('.clear-category').addEventListener('click', clearCategory);
    function clearCategory(){
        var categories = document.querySelectorAll('.filter-category:checked');

        for (var i = categories.length - 1; i >= 0; i--) {
            categories[i].checked = false;
        }

        filterProducts();
        removeFilteredCount();
    }

    document.querySelector('.clear-group').addEventListener('click', clearGroup);
    function clearGroup(){
        document.getElementById('filter-group').value = '';
        document.getElementById('filter-group-tags').innerHTML = '';

        filterProducts();
        removeFilteredCount();
    }

    document.querySelector('.clear-country').addEventListener('click', clearCountry);
    function clearCountry(){
        var countries = document.querySelectorAll('.filter-country:checked');

        for (var i = countries.length - 1; i >= 0; i--) {
            countries[i].checked = false;
        }

        filterProducts();
        removeFilteredCount();
    }


    function removeFilteredCount(){
        var filterCounts = document.querySelectorAll('.filters-count');
        for(var i = 0; i < filterCounts.length; i++){
            filterCounts[i].classList.remove('show');
        }
    }

    function filterProducts() {
        var cards = document.querySelectorAll('.card--product');
        var categoryHeadings = document.querySelectorAll('.category-title');
        var categoryHeadingsShow = [];

        for (var i = cards.length - 1; i >= 0; i--) {
            if (testFilterText(cards[i]) && testCategories(cards[i]) && testAccess(cards[i]) && testLocation(cards[i]) && testGroup(cards[i])) {
                cards[i].style.display = 'inherit';
                cards[i].classList.add('display-cards');
                categoryHeadingsShow.push(cards[i].dataset.category);
                continue;
            }

            cards[i].classList.remove('display-cards');
            cards[i].style.display = 'none';
        }

        for (var i = categoryHeadings.length - 1; i >= 0; i--) {
            if (categoryHeadingsShow.indexOf(categoryHeadings[i].dataset.category) !== -1) {
                categoryHeadings[i].style.display = 'inherit';
                continue;
            }

            categoryHeadings[i].style.display = 'none';
        }

        var allCategories = document.querySelectorAll('.category');

        for(var i =0; i < allCategories.length; i++){
            var cardsDisplay = allCategories[i].querySelectorAll('.display-cards');

            if(cardsDisplay){
                var filterCounts = allCategories[i].querySelector('.filters-count');
                filterCounts.classList.add('show');
                filterCounts.innerHTML =`${cardsDisplay.length} of `;
            }
        }

        var countDisplayCards = document.querySelectorAll('.display-cards');
        var noProducts = document.querySelector('.no-products-available');

        if(countDisplayCards.length === 0){
            noProducts.classList.add('show');
            return;
        }

        noProducts.classList.remove('show');
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
            if (categories[i].value === card.dataset.category) {
                return true;
            }
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
        var locations = document.querySelectorAll('.filter-country:checked');
        
        if (locations.length === 0 || card.dataset.locations === undefined) return true;

        for (var i = locations.length - 1; i >= 0; i--) {
            if (card.dataset.locations.split(',').indexOf(locations[i].value) !== -1) return true;
        }

        return false;
    }


    function testGroup(card) {
        var groups = document.querySelectorAll('#filter-group :checked');
        if (groups.length === 0 || card.dataset.group === undefined) return true;

        for (var i = groups.length - 1; i >= 0; i--) {
            if (groups[i].innerHTML === card.dataset.group) {
                return true;
            }
        }
        return false;
    }

}());

window.addEventListener('load', function(){
    localStorage.removeItem("category");
    var cards = document.querySelectorAll('.card--product');
})

var filterButton = document.querySelector('.filter-show-mobile');
filterButton.addEventListener('click', collapseSidebar);

function collapseSidebar(){
    var sideBar = document.querySelector('#sidebar');
    sideBar.classList.toggle('collapse');
}
