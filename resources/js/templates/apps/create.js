(function () {
    /* Search */
    function debounce() {
        if (timeout) {
            clearTimeout(timeout);
            timeout = null;
        }
        timeout = setTimeout(filterProducts, 512);
    }
    document.getElementById('filter-text').addEventListener('input', debounce);

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
            if (testCategories(cards[i]) && testLocation(cards[i]) && testGroup(cards[i]) && testFilterText(cards[i])) {
                cards[i].style.display = 'block';
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
        }

        var countDisplayCards = document.querySelectorAll('.display-cards');

        if(countDisplayCards.length === 0){
            /*********** DISPLAY NOTHING FOUND ************/
            // noProducts.classList.add('show');
            // createAppActions.classList.add('hide');
            return;
        }

        /******* IF RESULTS REMOVE NOTHING FOUND ************/
        // noProducts.classList.remove('show');
        // createAppActions.classList.remove('hide');
    }

    var countryInputElement = document.getElementById('country');
    countryInputElement.addEventListener('change', filterProducts);

    function testFilterText(card) {
        var filterText = document.getElementById('filter-text').value.toLowerCase();
        var title = card.dataset.title.toLowerCase();

        if (filterText === '') return true;
        if (title.indexOf(filterText) !== -1) return true;

        return false;
    }

    function testCategories(card) {
        var categories = document.querySelectorAll('.filter-category:checked');

        if (categories.length === 0) return true;

        for (var i = categories.length - 1; i >= 0; i--) {
            if (categories[i].value === card.dataset.category) {
                console.log(card.dataset.category, true)
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
        var locations = document.getElementById('country').value;

        // if (locations.length === 0 || card.dataset.locations === undefined) return true;
        //
        // for (var i = locations.length - 1; i >= 0; i--) {
        //     if (card.dataset.locations.split(',').indexOf(locations[i].value) !== -1) return true;
        // }

        if (card.dataset.locations.split(',').indexOf(locations) !== -1) return true;

        return false;
    }

}());

/* Variables */
var timeout = null;
var nav = document.querySelector('.content nav');

var buttons = document.querySelectorAll('.next');
var backButtons = document.querySelectorAll('.back');
var checkedBoxes = document.querySelectorAll('input[name=country-checkbox]:checked');
var default_option = document.querySelector('.default_option');
var select_wrap = document.querySelector('.select_wrap');
var inputData = document.querySelector('.selected-data');
var select_ul = document.querySelectorAll('.select_ul li');

/* Create Application Action */
var createForm = document.getElementById('create-app-form');
createForm.addEventListener('submit', handleCreate);

function init() {
    handleButtonClick();
    handleBackButtonClick();
}

document.querySelector('#name').addEventListener('keyup', appNameValidate);

function appNameValidate(){
    var specialChrs = /[`~!@#$%^&*|+=?;:±§'",.<>\{\}\[\]\\\/]/gi;

    this.value = this.value.replace(/  +/g, ' ');

    if(specialChrs.test(this.value)){
        this.value = this.value.replace(specialChrs, '');
        addAlert('warning', 'Application name cannot contain special characters.');
    }
}


default_option.addEventListener('click', function(){
    select_wrap.classList.toggle('active');
});

for(var i = 0; i < select_ul.length; i++){
    select_ul[i].addEventListener('click', toggleSelectList);
}

function toggleSelectList(){
    var selectedDataObject = this.querySelector('.select-data');
    default_option.innerHTML = selectedDataObject.innerHTML;
    inputData.setAttribute('value', selectedDataObject.dataset.createdby);
    inputData.setAttribute('data-teamid', selectedDataObject.dataset.teamid);
    select_wrap.classList.remove('active');
}

function handleButtonClick() {
    var elements =  createForm.elements;
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener('click', function (event) {
            var errors = [];
            var urlValue = elements['url'].value;
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

                    var appName =  elements['name'].value;
                    var checkUrl = document.getElementById('name').dataset.checkurl;

                    var app = {
                        name: appName,
                    }

                    var xhr = new XMLHttpRequest();
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



function filterLocations(selected) {
    var locations = document.querySelectorAll('.filtered-countries .block-location');

    for(var i = 0; i < locations.length; i++) {
        if (locations[i].dataset.location === selected) {
            locations[i].style.display = "flex";
            continue;
        }

        locations[i].style.display = "none";
    }
}

function filterCountryProducts(selectedCountry) {
    var products = document.querySelectorAll(".card--product");
    var categoryHeadings = document.querySelectorAll(".category-heading");
    var showCategories = [];
    var locations = null;

    for (var i = products.length - 1; i >= 0; i--) {
        products[i].style.display = "none";

        if(!products[i].dataset.locations) continue;
        locations =
            products[i].dataset.locations !== undefined
                ? products[i].dataset.locations.split(",")
                : ["all"];

        if(locations[0] === 'all' || locations.indexOf(selectedCountry) !== -1){
            products[i].style.display = "flex";

            if(showCategories.indexOf(products[i].dataset.category) === -1){
                showCategories.push(products[i].dataset.category);
            }
        }
    }

    for (var i = categoryHeadings.length - 1; i >= 0; i--) {
        categoryHeadings[i].style.display = showCategories.indexOf(categoryHeadings[i].dataset.category) === -1 ? "none" : "inherit";
    }
}

function handleCreate(event) {
    var elements = this.elements;
    var app = {
        display_name: elements['name'].value,
        url: elements['url'].value,
        description: elements['description'].value,
        country: document.querySelector('.country-checkbox:checked').dataset.location,
        products: [],
        team_id: inputData.dataset.teamid
    };
    var selectedProducts = document.querySelectorAll('.add-product:checked');
    var button = document.getElementById('create');
    var url = document.getElementById('create-app-form').action;
    var xhr = new XMLHttpRequest();

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
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

            if(result.errors) {
                result.message = [];
                for(var error in result.errors){
                    result.message.push(result.errors[error]);
                }
            }

            addAlert('error', result.message || 'Sorry there was a problem creating your app. Please try again.');

            button.removeAttribute('disabled');
        }
    };
}
