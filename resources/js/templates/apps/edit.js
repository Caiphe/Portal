(function () {
    let timeout = null;
    let categoryCheckboxes = document.querySelectorAll('.filter-category');
    document.getElementById('cancel').addEventListener('click', handleCancelButtonClickEvent)

    function handleCancelButtonClickEvent(event) {
        event.preventDefault();
        if (window.confirm('Are you sure you want to leave this page?') === true) {
            window.location.replace(this.dataset.backUrl);
        }
    }

    window.addEventListener('load', filterProducts);

    document.querySelector('.clear-category').addEventListener('click', clearCategory);

    function clearCategory() {
        let categories = document.querySelectorAll('.filter-category:checked');
        for (let i = categories.length - 1; i >= 0; i--) {
            categories[i].checked = false;
        }
        filterProducts();
    }

    /* Clear category group */
    document.querySelector('.clear-group').addEventListener('click', clearGroup);

    function clearGroup() {
        document.getElementById('filter-group').value = '';
        document.getElementById('filter-group-tags').innerHTML = '';
        filterProducts();
    }

    for (let i = categoryCheckboxes.length - 1; i >= 0; i--) {
        categoryCheckboxes[i].addEventListener('change', filterProducts);
    }

    function debounce() {
        if (timeout) {
            clearTimeout(timeout);
            timeout = null;
        }
        timeout = setTimeout(filterProducts, 512);
    }
    document.getElementById('filter-text').addEventListener('input', debounce);
    document.getElementById('filter-group').addEventListener('change', filterProducts);

    function filterProducts() {
        let cards = document.querySelectorAll('.card--product');
        let categoryHeadings = document.querySelectorAll('.category-title');
        let categoryHeadingsShow = [];

        for (let i = cards.length - 1; i >= 0; i--) {
            if (testCategories(cards[i]) && testLocation(cards[i]) && testGroup(cards[i]) && testFilterText(cards[i])) {
                cards[i].style.display = 'flex';
                cards[i].classList.add('display-cards');
                categoryHeadingsShow.push(cards[i].dataset.category);
                continue;
            }
            cards[i].classList.remove('display-cards');
            cards[i].style.display = 'none';
        }

        for (let i = categoryHeadings.length - 1; i >= 0; i--) {
            if (categoryHeadingsShow.indexOf(categoryHeadings[i].dataset.category) !== -1) {
                categoryHeadings[i].style.display = 'inherit';
                continue;
            }
            categoryHeadings[i].style.display = 'none';
        }

        let countDisplayCards = document.querySelectorAll('.display-cards');
        let noProductsDisplayElement = document.getElementById('no-products');
        let ProductsDisplayElement = document.getElementById('product-list-selection');

        if (countDisplayCards.length === 0) {
            noProductsDisplayElement.style.visibility = 'visible';
            noProductsDisplayElement.style.display = 'block';
            ProductsDisplayElement.style.visibility = 'hidden';
            ProductsDisplayElement.style.display = 'none';
            return;
        }

        noProductsDisplayElement.style.visibility = 'hidden';
        noProductsDisplayElement.style.display = 'none';
        ProductsDisplayElement.style.visibility = 'visible';
        ProductsDisplayElement.style.display = 'block';
    }

    // Filters options
    function testFilterText(card) {
        let filterText = document.getElementById('filter-text').value.toLowerCase();
        let title = card.dataset.title.toLowerCase();

        if (filterText === '') return true;

        return title.indexOf(filterText) !== -1;
    }

    function testCategories(card) {
        let categories = document.querySelectorAll('.filter-category:checked');

        if (categories.length === 0) return true;

        for (let i = categories.length - 1; i >= 0; i--) {
            if (categories[i].value === card.dataset.category) {
                return true;
            }
        }

        return false;
    }

    function testGroup(card) {
        let groups = document.querySelectorAll('#filter-group :checked');
        if (groups.length === 0 || card.dataset.group === undefined) return true;

        for (let i = groups.length - 1; i >= 0; i--) {
            if (groups[i].innerHTML === card.dataset.group) {
                return true;
            }
        }

        return false;
    }

    function testLocation(card) {
        let locations = document.getElementById('selectedCountry').value;
        return card.dataset.locations.split(',').indexOf(locations) !== -1;
    }
    // End of filters options

    // Update app form submit
    document.getElementById('complete').addEventListener('click', handleUpdate);
    function handleUpdate(event) {
        event.preventDefault();

        let appForm = document.getElementById('form-edit-app');
        let elements = appForm.elements;

        let channels = document.querySelectorAll('input[name="channels[]"]:checked');
        let channels_array = [];
        channels.forEach((channel) => {
            channels_array.push(channel.value);
        });

        let products = document.querySelectorAll('input[name="add_product[]"]:checked');
        let products_array = [];
            products.forEach((product) => {
                products_array.push(product.value);
            });

        if (products.length === 0) {
            return addAlert('error', 'Please select at least one product.')
        }

        let data = {
            display_name: elements['name'].value,
            entity_name : elements['entity_name'].value,
            contact_number : elements['contact_number'].value,
            url: elements['url'].value,
            description: elements['description'].value,
            country: document.querySelector('#selectedCountry').value,
            products: products_array,
            channels: channels_array,
            _method: 'PUT'
        };

        let xhr = new XMLHttpRequest();

        addLoading('Updating...');

        xhr.open('POST', appForm.action, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', elements['_token'].value);
        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.send(JSON.stringify(data));

        xhr.onload = function() {
            removeLoading();

            if (xhr.status === 200) {
                addAlert('success', ['Application updated successfully', 'You will be redirected to your app page shortly.'], function(){
                    window.location.href = appForm.dataset.redirect;
                });
            } else {
                let result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

                if(result.errors) {
                    result.message = [];
                    for(let error in result.errors){
                        result.message.push(result.errors[error]);
                    }
                }

                addAlert('error', result.message || 'Sorry there was a problem updating your app. Please try again.');
            }
        };
    }
}());
