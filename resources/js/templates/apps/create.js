(function () {
    var productAddBtns = document.querySelectorAll('.add-product-btn');
    for (var i = 0; i < productAddBtns.length; i++) {
        productAddBtns[i].addEventListener('click', addProductFunc);
    }

    var nextBlockBtn = document.querySelector('#next-block-btn');
    if(nextBlockBtn){
        nextBlockBtn.addEventListener('click', filterProducts);
    }

    function addProductFunc() {
        var product = this.parentElement.parentElement;
        product.classList.toggle('selected');
    }
    
    var countries = document.querySelectorAll('.country');
    for (var l = 0; l < countries.length; l++) {
        countries[l].addEventListener('change', filterProducts);
    }
    
    var filterProductsEls = document.querySelectorAll('.filter-products');
    document.getElementById('filter-group').addEventListener('change', filterProducts);

    for (var i = filterProductsEls.length - 1; i >= 0; i--) {
        filterProductsEls[i].addEventListener('change', filterProducts);
    }

    // Clear category
    document.querySelector('.clear-category').addEventListener('click', clearCategory);
    function clearCategory(){
        var categories = document.querySelectorAll('.filter-category:checked');

        for (var i = categories.length - 1; i >= 0; i--) {
            categories[i].checked = false;
        }

        filterProducts();
    }

    document.querySelector('.clear-group').addEventListener('click', clearGroup);
    function clearGroup(){
        document.getElementById('filter-group').value = '';
        document.getElementById('filter-group-tags').innerHTML = '';

        filterProducts();
    }

    function filterProducts() {
        var cards = document.querySelectorAll('.card--product');
        var categoryHeadings = document.querySelectorAll('.category-title');
        var categoryHeadingsShow = [];

        for (var i = cards.length - 1; i >= 0; i--) {
            if (testCategories(cards[i]) && testLocation(cards[i]) && testGroup(cards[i])) {
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
                filterCounts.innerHTML =`${cardsDisplay.length} Products`;
            }
        }

        var countDisplayCards = document.querySelectorAll('.display-cards');
        var noProducts = document.querySelector('.no-products-available');
        var createAppActions = document.querySelector('.create-apps-actions');

        if(countDisplayCards.length === 0){
            noProducts.classList.add('show');
            createAppActions.classList.add('hide');
            return;
        }

        noProducts.classList.remove('show');
        createAppActions.classList.remove('hide');
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

    function testLocation(card) {
        var locations = document.querySelectorAll('.filter-country:checked');
        
        if (locations.length === 0 || card.dataset.locations === undefined) return true;

        for (var i = locations.length - 1; i >= 0; i--) {
            if (card.dataset.locations.split(',').indexOf(locations[i].value) !== -1) return true;
        }

        return false;
    }

}());