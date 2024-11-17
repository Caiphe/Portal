(function () {
    /* Variables */
    let timeout = null;
    let nav = document.querySelector('.content nav');
    let buttons = document.querySelectorAll('.next');
    let inputData = document.querySelector('.selected-data');
    let select_wrap = document.querySelector('.select_wrap');
    let select_ul = document.querySelectorAll('.select_ul li');

    /* Create Application Action */
    let createForm = document.getElementById('create-app-form');
    createForm.addEventListener('submit', handleCreate);

    function init() {
        handleButtonClick();
        // handleBackButtonClick();
    }

    /* Cancel Button */
    let cancelButtonElement = document.getElementById('cancel');
    cancelButtonElement.addEventListener('click', handleCancelButtonClickEvent)

    function handleCancelButtonClickEvent(event) {
        event.preventDefault();
        if(window.confirm('Are you sure you want to leave this page?') === true) {
            window.location.replace(this.dataset.backUrl);
        }
    }

    /* Teams */
    let default_option = document.querySelector('.default_option');

    default_option.addEventListener('click', function(){
        select_wrap.classList.toggle('active');
    });

    for(let i = 0; i < select_ul.length; i++){
        select_ul[i].addEventListener('click', toggleSelectList);
    }

    function toggleSelectList(){
        let selectedDataObject = this.querySelector('.select-data');
        default_option.innerHTML = selectedDataObject.innerHTML;
        inputData.setAttribute('value', selectedDataObject.dataset.createdby);
        inputData.setAttribute('data-teamid', selectedDataObject.dataset.teamid);
        select_wrap.classList.remove('active');
    }

    function handleButtonClick() {
        let elements =  createForm.elements;
        for (let i = 0; i < buttons.length; i++) {
            buttons[i].addEventListener('click', function (event) {
                let errors = [];
                let urlValue = elements['url'].value;
                event.preventDefault();

                if( createForm.firstElementChild.classList.contains('active')) {

                    if(urlValue !== '' && !/https?:\/\/.*\..*/.test(urlValue)) {
                        errors.push({msg: 'Please add a valid url. Eg. https://callback.com', el: elements['url']});
                    } else {
                        elements['url'].nextElementSibling.textContent = '';
                    }

                    if(elements['name'].value === '') {
                        addAlert('error', 'Please add your app name');
                        elements['name'].focus();
                    } else if(elements['name'].value.length === 1){
                        elements['name'].focus();
                        addAlert('warning', 'Please provide a valid app name');
                    } else if(elements['name'].value !== '' && elements['name'].value.length > 1 ){

                        let appName =  elements['name'].value;
                        let checkUrl = document.getElementById('name').dataset.checkurl;

                        let app = {
                            name: appName,
                        }

                        let xhr = new XMLHttpRequest();
                        addLoading("checking app's name...");

                        xhr.open('POST', checkUrl, true);
                        xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
                        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
                        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                        xhr.send(JSON.stringify(app));

                        xhr.onload = function() {
                            removeLoading();

                            if(xhr.status === 409){
                                elements['name'].focus();
                                errors.push({msg: "App name already exists", el: elements['name']});
                            }

                            if(errors.length > 0){
                                for (var i = errors.length - 1; i >= 0; i--) {
                                    errors[i].el.nextElementSibling.textContent = errors[i].msg;
                                }

                                return;
                            }

                            nav.querySelector('a').nextElementSibling.classList.add('active');
                            createForm.firstElementChild.classList.remove('active');
                            createForm.firstElementChild.style.display = 'none';
                            createForm.firstElementChild.nextElementSibling.classList.add('active');
                        };
                    }

                } else if ( createForm.firstElementChild.nextElementSibling.classList.contains('active')) {
                    if(document.querySelectorAll('.country-checkbox:checked').length === 0) {
                        return void addAlert('error', 'Please select a country');
                    }

                    nav.querySelector('a').nextElementSibling.nextElementSibling.classList.add('active');

                    createForm.firstElementChild.nextElementSibling.classList.remove('active');
                    createForm.firstElementChild.nextElementSibling.nextElementSibling.classList.add('active');
                }
            });
        }
    }

    // function filterLocations(selected) {
    //     var locations = document.querySelectorAll('.filtered-countries .block-location');
    //
    //     for(var i = 0; i < locations.length; i++) {
    //         if (locations[i].dataset.location === selected) {
    //             locations[i].style.display = "flex";
    //             continue;
    //         }
    //
    //         locations[i].style.display = "none";
    //     }
    // }

    // function filterCountryProducts(selectedCountry) {
    //     var products = document.querySelectorAll(".card--product");
    //     var categoryHeadings = document.querySelectorAll(".category-heading");
    //     var showCategories = [];
    //     var locations = null;
    //
    //     for (var i = products.length - 1; i >= 0; i--) {
    //         products[i].style.display = "none";
    //
    //         if(!products[i].dataset.locations) continue;
    //         locations =
    //             products[i].dataset.locations !== undefined
    //                 ? products[i].dataset.locations.split(",")
    //                 : ["all"];
    //
    //         if(locations[0] === 'all' || locations.indexOf(selectedCountry) !== -1){
    //             products[i].style.display = "flex";
    //
    //             if(showCategories.indexOf(products[i].dataset.category) === -1){
    //                 showCategories.push(products[i].dataset.category);
    //             }
    //         }
    //     }
    //
    //     for (var i = categoryHeadings.length - 1; i >= 0; i--) {
    //         categoryHeadings[i].style.display = showCategories.indexOf(categoryHeadings[i].dataset.category) === -1 ? "none" : "inherit";
    //     }
    // }

    function handleCreate(event) {
        let elements = this.elements;
        let app = {
            display_name: elements['name'].value,
            url: elements['url'].value,
            description: elements['description'].value,
            country: document.querySelector('.country-checkbox:checked').dataset.location,
            products: [],
            team_id: inputData.dataset.teamid
        };
        let selectedProducts = document.querySelectorAll('.add-product:checked');
        let button = document.getElementById('create');
        let url = document.getElementById('create-app-form').action;
        let xhr = new XMLHttpRequest();

        event.preventDefault();

        for(i = 0; i < selectedProducts.length; i++) {
            app.products.push(selectedProducts[i].value);
        }

        if (app.products.length === 0) {
            return void addAlert('error', 'Please select at least one product.')
        }

        button.disabled = true;
        addLoading('Creating app...');

        xhr.open('POST', url);
        xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.send(JSON.stringify(app));

        xhr.onload = function() {
            removeLoading();

            if (xhr.status === 200) {
                addAlert('success', ['Application created successfully', 'You will be redirected to your app page shortly.'], function(){
                    window.location.href = "{{ route('app.index') }}";
                });}
            else if(xhr.status === 429){
                addAlert('warning', ['This action is not allowed.', 'Please contact your admin.'], function(){
                    window.location.href = "{{ route('app.index') }}";
                });
            }
            else if(xhr.status === 422){
                addAlert('error', [`An application with the name '${elements['name'].value}' already exists. Please wait, you will be redirected back to the app creation page where you can try a different name.`])
                setTimeout(function(){
                    location.reload();
                }, 6000);
            }
            else {
                let result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

                if(result.errors) {
                    result.message = [];
                    for(let error in result.errors){
                        result.message.push(result.errors[error]);
                    }
                }

                addAlert('error', result.message || 'Sorry there was a problem creating your app. Please try again.');

                button.removeAttribute('disabled');
            }
        };
    }

    /* Realtime application name check*/
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
            return;
        }

        let specialChrs = /[`~!@#$%^&*|+=?;:±§'",.<>\{\}\[\]\\\/]/gi;

        nameElement.value = nameElement.value.replace(/  +/g, ' ');

        if(specialChrs.test(nameElement.value)){
            nameElement.value = nameElement.value.replace(specialChrs, '');
            addAlert('warning', 'Application name cannot contain special characters.');
            return;
        }

        //check against names
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
                if(json.duplicate === true) {
                    nameCheckElement.classList.add('warning');
                    nameCheckElement.querySelector('p')
                        .innerText = 'You already have an application with this name, please use another.';
                    nameCheckElement.querySelector('img')
                        .src = '/images/icons/error-cross.png'
                } else {
                    nameCheckElement.classList.remove('show-flex');
                }
            });
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

    countryInputElement.addEventListener('change', function(event) {
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

    let productAddBtns = document.querySelectorAll('.add-product-btn');
    for (let i = 0; i < productAddBtns.length; i++) {
        productAddBtns[i].addEventListener('click', addProductFunc);
    }

    let nextBlockBtn = document.querySelector('#next-block-btn');
    if (nextBlockBtn){
        nextBlockBtn.addEventListener('click', filterProducts);
    }

    function addProductFunc() {
        let product = this.parentElement.parentElement;
        product.classList.toggle('selected');
    }

    // Clear products
    function clearProducts(){
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
    function clearCategory(){
        let categories = document.querySelectorAll('.filter-category:checked');

        for (let i = categories.length - 1; i >= 0; i--) {
            categories[i].checked = false;
        }

        filterProducts();
    }

    /* Clear category group */
    document.querySelector('.clear-group').addEventListener('click', clearGroup);
    function clearGroup(){
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

        let allCategories = document.querySelectorAll('.category');

        for(let i =0; i < allCategories.length; i++){
            let cardsDisplay = allCategories[i].querySelectorAll('.display-cards');
        }

        let countDisplayCards = document.querySelectorAll('.display-cards');
        let noProductsDisplayElement = document.getElementById('no-products');
        let ProductsDisplayElement = document.getElementById('product-list-selection');

        if(countDisplayCards.length === 0) {
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
        if (title.indexOf(filterText) !== -1) return true;

        return false;
    }

    function testCategories(card) {
        let categories = document.querySelectorAll('.filter-category:checked');

        if (categories.length === 0) return true;

        for (let i = categories.length - 1; i >= 0; i--) {
            if (categories[i].value === card.dataset.category) {
                console.log(card.dataset.category, true)
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

        if (card.dataset.locations.split(',').indexOf(locations) !== -1) return true;

        return false;
    }
}());
