(function () {
    /* Variables */
    let timeout = null;
    let inputData = document.querySelector('.selected-data');
    let select_wrap = document.querySelector('.select_wrap');
    let select_ul = document.querySelectorAll('.select_ul li');
    let beforeProducts = document.getElementById('before-products');
    let productsBlock = document.getElementById('product-selection');
    let countryElement = document.getElementById('country');

    window.addEventListener('load', function(){
        countryElement.value = '';
        beforeProducts.classList.remove('hide');
        productsBlock.classList.remove('show');
    })

    /* Cancel Button */
    let cancelButtonElement = document.getElementById('cancel');
    cancelButtonElement.addEventListener('click', handleCancelButtonClickEvent)

    function handleCancelButtonClickEvent(event) {
        event.preventDefault();
        if (window.confirm('Are you sure you want to leave this page?') === true) {
            window.location.replace(this.dataset.backUrl);
        }
    }

    /* Realtime application name check */
    let applicationNameElement = document.getElementById('name');
    applicationNameElement.addEventListener('keyup', applicationNameDebounce)

    function applicationNameDebounce() {
        if (timeout) {
            clearTimeout(timeout);
            timeout = null;
        }
        timeout = setTimeout(applicationNameCheck, 512);
    }

    function applicationNameCheck() {
        let nameCheckElement = document.getElementById('nameCheck');
        let checkUri = nameCheckElement.dataset.checkUri;
        let csrfToken = nameCheckElement.dataset.token;
        let nameElement = document.getElementById('name');

        nameCheckElement.querySelector('p')
            .innerText = 'Checking application name...';
        nameCheckElement.querySelector('img')
            .src = '/images/icons/loading.svg'
        nameCheckElement.classList.remove('warning');
        nameCheckElement.classList.add('show-flex');

        if (nameElement.value === '') {
            nameCheckElement.classList.remove('show-flex');
            nameElement.dataset.validationState = "invalid";
            validate.showError('name_error', 'Your application name cannot be empty.');
            return;
        } else {
            validate.hideError('name_error');
        }

        let specialChrs = /[`~!@#$%^&*|+=?;:±§'",.<>\[\]\\\/]/gi;

        nameElement.value = nameElement.value.replace(/  +/g, ' ');

        if (specialChrs.test(nameElement.value)) {
            nameCheckElement.classList.remove('show-flex');
            nameElement.value = nameElement.value.replace(specialChrs, '');
            addAlert('warning', 'Application name cannot contain special characters.');
            return;
        }

        // Check against names
        fetch(checkUri, {
            method: "POST",
            body: JSON.stringify({
                name: nameElement.value,
            }),
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            }
        })
            .then((response) => {
                return response.json();
            })
            .then((json) => {
                if (json.duplicate === true) {
                    nameCheckElement.classList.add('warning');
                    nameCheckElement.querySelector('p')
                        .innerText = 'You already have an application with this name, please use another.';
                    nameCheckElement.querySelector('img')
                        .src = '/images/icons/error-cross.png'
                    nameElement.dataset.validationState = "invalid";
                } else {
                    nameCheckElement.classList.remove('show-flex');
                    nameElement.dataset.validationState = "valid";
                }
            });
    }

    /* Validate Form Elements As User Interacts */
    const validate = {
        showError(elementID, errorResponseText) {
            const e = document.getElementById(elementID);
            e.innerText = errorResponseText;
            e.style.visibility = 'visible';
            e.style.display = 'block';
        },

        hideError(elementID) {
            const e = document.getElementById(elementID);
            e.style.visibility = 'hidden';
            e.style.display = 'none';
        },

        /**
         * This checks if the application name has a valid state. There is other code which
         * checks and sets validity, but this one manages the programmatic check where needed.
         * @param elementID
         * @return boolean
         */
        checkAppName(elementID) {
            const e = document.getElementById(elementID);
            const eValidationState = e.dataset.validationState;
            return eValidationState === "valid";
        },

        /**
         * This very simply checks if a field has been completed, because it is required.
         * The value itself is not checked.
         * @param elementID
         * @return boolean
         */
        required(elementID) {
            const e = document.getElementById(elementID);
            return e.value !== '';
        },

        /**
         * This checks that the given form element array of checkboxes has a specific number of checkboxes
         * selected.
         */
        checked(inputName, min) {
            const checkboxes = document.querySelectorAll('input[name="' + inputName + '[]"]:checked');
            return checkboxes.length >= min;
        },
    }

    /* Complete Button */
    let completeButtonElement = document.getElementById('complete');
    completeButtonElement.addEventListener('click', handleCompleteButtonClickEvent);

    function handleCompleteButtonClickEvent() {
        let formError = false;

        // Validate the application name
        if (!validate.checkAppName('name')) {
            validate.showError('name_error', 'The application name is not valid.');
            formError = true;
        }

        // Validate the required fields
        if (!validate.required('entity_name')) {
            validate.showError('entity_name_error', 'Entity name is required.')
            formError = true;
        } else {
            validate.hideError('entity_name_error');
        }

        if (!validate.required('contact_number')) {
            validate.showError('contact_number_error', 'Contact Number is required.');
            formError = true;
        } else if (!validateContactNumber(document.getElementById('contact_number').value)) {
            validate.showError('contact_number_error', 'Invalid Contact Number format.');
            formError = true;
        } else {
            validate.hideError('contact_number_error');
        }

        if (!validate.required('country')) {
            validate.showError('country_error', 'Select a country to continue.');
            formError = true;
        } else {
            validate.hideError('country_error');
        }

        // Validate that at least one checkbox is checked
        if (!validate.checked('channels', 1)) {
            validate.showError('channel_error', 'Select at least one channel to continue.');
            formError = true;
        } else {
            validate.hideError('channel_error');
        }

        // Validate that at least one country is selected
        if (!validate.checked('add_product', 1)) {
            validate.showError('product_error', 'Select at least one product to continue.');
            formError = true;
        } else {
            validate.hideError('product_error');
        }

        if (formError === true) {
            return;
        }

        // Function to validate contact number format
        function validateContactNumber(number) {
            const contactNumberRegex = /^\+?[\d\s\-()]{7,20}$/;
            return contactNumberRegex.test(number);
        }

        // Create new application with data provided
        let createAppForm = document.getElementById('create-app-form');
        let csrfToken = document.querySelector("input[name='_token']").value;

        let name = document.getElementById('name').value;
        let entity_name = document.getElementById('entity_name').value;
        let contact_number = document.getElementById('contact_number').value;
        let url = document.getElementById('url').value;
        let description = document.getElementById('description').value;
        let country = document.getElementById('country').value;
        let beforeProducts = document.getElementById('before-products');
        let productsBlock = document.getElementById('product-selection');


        window.addEventListener('load', function(){
            country = '';
            beforeProducts.classList.remove('hide');
            productsBlock.classList.remove('show');
        })

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

        addLoading("Saving New Application ...");

        let createFetch = fetch(createAppForm.action, {
            method: "POST",
            body: JSON.stringify({
                name: name,
                display_name: name,
                url: url,
                description: description,
                country: country,
                products: products_array,
                channels: channels_array,
                entity_name: entity_name,
                contact_number: contact_number,
                team_id: inputData.dataset.teamid
            }),
            headers: {
                "Content-Type": "application/json; charset=utf-8",
                "X-CSRF-TOKEN": csrfToken,
                "X-Requested-With": "XMLHttpRequest",
            }
        });

        createFetch.then((response) => {
            if (response.status === 200) {
                addAlert('success', ['Application created successfully', 'You will be redirected to your app page shortly.'], function () {
                    window.location.replace(createAppForm.dataset.redirect);
                });
            }
            else if(response.status === 409) {
                addAlert('error', 'You already have an application with this name, please use another.');
            } else {

                let result = response.responseText ? JSON.parse(response.responseText) : null;

                if(result.errors) {
                    result.message = [];
                    for(let error in result.errors){
                        result.message.push(result.errors[error]);
                    }
                }

                addAlert('error', result.message || 'Sorry there was a problem creating your app. Please try again.');
            }
        });

        createFetch.finally(() => {
            removeLoading();
        });
    }

    /* Teams Selection */
    let default_option = document.querySelector('.default_option');

    default_option.addEventListener('click', function () {
        select_wrap.classList.toggle('active');
    });

    for (let i = 0; i < select_ul.length; i++) {
        select_ul[i].addEventListener('click', toggleSelectList);
    }

    function toggleSelectList() {
        let selectedDataObject = this.querySelector('.select-data');
        default_option.innerHTML = selectedDataObject.innerHTML;
        inputData.setAttribute('value', selectedDataObject.dataset.createdby);
        inputData.setAttribute('data-teamid', selectedDataObject.dataset.teamid);
        select_wrap.classList.remove('active');
    }

    /* Search */
    function debounce() {
        if (timeout) {
            clearTimeout(timeout);
            timeout = null;
        }
        timeout = setTimeout(filterProducts, 512);
    }

    document.getElementById('filter-text').addEventListener('input', debounce);

    /* Country Selection */
    let countryInputElement = document.getElementById('country');

    countryInputElement.addEventListener('change', function () {
        let countryElement = document.getElementById('country');
        let countryDisplayElement = document.getElementById('flag-country');
        let products = document.getElementById('product-selection');
        let beforeProducts = document.getElementById('before-products');

        clearProducts();
        clearGroup();
        clearCategory();

        if (countryElement.value !== '') {
            // update the country view
            countryDisplayElement.querySelector('p').innerText = countryElement.options[countryElement.selectedIndex].innerText;
            countryDisplayElement.querySelector('img').src = '/images/locations/' + countryElement.value + '.svg'

            // update the hide/show status
            beforeProducts.classList.add('hide');
            products.classList.add('show');

            return;
        }

        // Hide products if no country is selected
        beforeProducts.classList.remove('hide');
        products.classList.remove('show');

        // Filter products
        filterProducts();
    });

    /* Clear Products */
    function clearProducts() {
        let productsElements = document.querySelectorAll("input[name='add_product[]']:checked");
        for (let i = productsElements.length - 1; i >= 0; i--) {
            productsElements[i].checked = false;
        }
    }

    let filterProductsEls = document.querySelectorAll('.filter-products');
    document.getElementById('filter-group').addEventListener('change', filterProducts);
    for (let i = filterProductsEls.length - 1; i >= 0; i--) {
        filterProductsEls[i].addEventListener('change', filterProducts);
    }

    /* Clear category */
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

    /* Filter products */
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
        let locations = document.getElementById('country').value;
        return card.dataset.locations.split(',').indexOf(locations) !== -1;
    }
}());
