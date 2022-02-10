(function () {
    var nav = document.querySelector('.content nav');
    var form = document.getElementById('form-create-app');
    var backButtons = document.querySelectorAll('.back');
    var appCreatorEmail = adminAppsCreateLookup('appCreatorEmail');
    var suggestions = adminAppsCreateLookup('userEmails');
    var profiles = adminAppsCreateLookup('userProfiles');
    var searchWrapper = document.querySelector(".search-input");
    var searchField = searchWrapper.querySelector(".search-field");
    var suggBox = searchWrapper.querySelector(".autocom-box");
    var creatorEmail = document.querySelector(".creator-email");
    var removeThumbnail = document.getElementById('remove-assignee');
    var ownerAvatar = document.querySelector(".owner-avatar");
    var buttonsContainer = document.querySelector(".apps-button-container");
    var addProductButtons = document.querySelectorAll('[data-title] a');

    document.getElementById('next-app-owner').addEventListener('click', nextAppOwner);
    document.getElementById('next-app-details').addEventListener('click', nextAppDetails);
    document.getElementById('next-select-products').addEventListener('click', nextSelectProducts);
    document.getElementById('next-create-app').addEventListener('click', nextCreateApp);
    searchField.addEventListener('keyup', searchFieldSuggestions);

    for (var j = 0; j < backButtons.length; j++) {
        backButtons[j].addEventListener('click', back);
    }

    document.getElementById('assign-to-me').addEventListener('click', assignToMe);

    removeThumbnail.style.display = 'none';

    function assignToMe() {
        select(appCreatorEmail);
        removeThumbnail.style.display = '';
    }

    function nextAppOwner() {
        if (searchField.value === '') {
            return void addAlert('error', 'Please add a valid email');
        }

        next();
    }

    function nextAppDetails() {
        var urlValue = null;

        if (document.getElementById('name').value === '') {
            return void addAlert('error', 'Please add a name for your app');
        }

        urlValue = document.getElementById('url').value;

        if (urlValue !== '' && !/https?:\/\/.*\..*/.test(urlValue)) {
            return void addAlert('error', ['Please add a valid url', 'Eg. https://callback.com']);
        }

        next();
    }

    function nextSelectProducts() {
        if (document.querySelectorAll('.country-checkbox:checked').length === 0) {
            return void addAlert('error', 'Please select a country');
        }

        next();
    }

    function nextCreateApp() {
        if (document.querySelectorAll('.products .selected .buttons .done').length === 0) {
            return void addAlert('error', 'Please select at least one product');
        }

        handleCreate();
    }

    function next() {
        var navActive = nav.querySelector('.active');
        var sectionActive = form.querySelector('.create-app-section.active');

        navActive.classList.remove('active');
        navActive.nextElementSibling.classList.add('active');

        sectionActive.classList.remove('active');
        sectionActive.nextElementSibling.classList.add('active');
    }

    function back() {
        var navActive = nav.querySelector('.active');
        var sectionActive = form.querySelector('.create-app-section.active');

        navActive.classList.remove('active');
        navActive.previousElementSibling.classList.add('active');

        sectionActive.classList.remove('active');
        sectionActive.previousElementSibling.classList.add('active');
    }

    document.querySelector('[type="reset"]').addEventListener('click', function () {
        if (form.querySelector('.active') !== form.firstElementChild) {
            form.querySelector('.active').classList.remove('active');
            form.firstElementChild.classList.add('active');
            form.firstElementChild.style.display = 'flex';
        }

        var els = document.querySelectorAll('#app-create nav a.active');
        var countries = document.querySelectorAll('.countries .selected');
        var products = document.querySelectorAll('.products .selected');
        var buttons = document.querySelectorAll('.products .selected .done');

        for (var k = 0; k < els.length; k++) {
            els[k].classList.remove('active');
        }

        for (var x = 0; x < countries.length; x++) {
            countries[x].classList.remove('selected');
        }

        for (var z = 0; z < products.length; z++) {
            products[z].classList.remove('selected');
        }

        for (var w = 0; w < buttons.length; w++) {
            buttons[w].classList.remove('done');
            buttons[w].classList.add('plus');
        }

        nav.querySelector('button').classList.add('active');

        form.reset();
    });

    var countries = document.querySelectorAll('.country');
    for (var l = 0; l < countries.length; l++) {
        countries[l].addEventListener('change', selectCountry);
    }

    function selectCountry() {
        var countryRadioBoxes = document.querySelectorAll('.country-checkbox:checked')[0];
        var selected = countryRadioBoxes.dataset.location;

        filterLocations(selected);
        filterProducts(selected);

        nextSelectProducts();
    }

    function filterLocations(selected) {
        var locations = document.querySelectorAll('.filtered-countries img');

        for (var i = 0; i < locations.length; i++) {
            if (locations[i].dataset.location === selected) {
                locations[i].style.opacity = "1";
                continue;
            }

            locations[i].style.opacity = "0.15";
        }
    }

    function filterProducts(selectedCountry) {
        var products = document.querySelectorAll(".card--product");
        var categories = document.querySelectorAll(".category");
        var availabelCategories = [];
        var locations = null;

        for (var i = products.length - 1; i >= 0; i--) {
            products[i].style.display = "none";

            locations = products[i].dataset.locations !== undefined ? products[i].dataset.locations.split(",") : ["all"];

            if (locations[0] === 'all' || locations.indexOf(selectedCountry) !== -1) {
                products[i].style.display = "flex";
                availabelCategories.push(products[i].dataset.category);
            }
        }

        for (var i = categories.length - 1; i >= 0; i--) {
            if (availabelCategories.indexOf(categories[i].dataset.category) !== -1) {
                categories[i].style.display = 'flex';
            } else {
                categories[i].style.display = 'none';
            }
        }
    }

    for (var o = 0; o < addProductButtons.length; ++o) {

        addProductButtons[o].addEventListener('click', function (event) {
            var button = event.currentTarget;

            button.classList.toggle('plus');
            button.classList.toggle('done');

            if (document.querySelectorAll('[data-title] button')) {
                var selectedProduct = this.parentNode.parentNode;

                selectedProduct.classList.toggle('selected');
            }
        });
    }

    function handleCreate() {
        var elements = form.elements;
        var selectedProducts = document.querySelectorAll('.products .selected .buttons a:last-of-type');
        var button = document.getElementById('next-create-app');
        var app = {
            app_owner: elements['app-owner'].value,
            display_name: elements['name'].value,
            url: elements['url'].value,
            description: elements['description'].value,
            country: document.querySelector('.country-checkbox:checked').dataset.location,
            products: [],
        };

        for (i = 0; i < selectedProducts.length; i++) {
            app.products.push(selectedProducts[i].dataset.name);
        }

        if (app.products.length === 0) {
            return void addAlert('error', 'Please select at least one product.')
        }

        button.disabled = true;
        
        addLoading('Creating app...');

        var url = adminAppsCreateLookup('appStoreUrl');
        var xhr = new XMLHttpRequest();

        xhr.open('POST', url);
        xhr.setRequestHeader('X-CSRF-TOKEN', adminAppsCreateLookup('csrfToken'));
        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.send(JSON.stringify(app));

        xhr.onload = function () {
            button.removeAttribute('disabled');
            removeLoading();

            if (xhr.status === 200) {
                return void next();
            }

            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

            if (result.errors) {
                result.message = [];
                for (var error in result.errors) {
                    result.message.push(result.errors[error]);
                }
            }

            addAlert('error', result.message || 'Sorry there was a problem creating your app. Please try again.');
        };
    }

    // if user press any key and release
    function searchFieldSuggestions(e) {
        var userData = e.target.value; //user enetered data
        var emptyArray = [];
        if (userData) {
            emptyArray = suggestions.filter((data) => {
                //filtering array value and user characters to lowercase and return only those words which are start with user enetered chars
                return data.toLocaleLowerCase().startsWith(userData.toLocaleLowerCase());
            });
            emptyArray = emptyArray.map((data) => {
                // passing return data inside li tag
                return data = `<li>${data}</li>`;
            });
            searchWrapper.classList.add("active"); //show autocomplete box
            showSuggestions(emptyArray);
            var allList = suggBox.querySelectorAll("li");
            for (var i = 0; i < allList.length; i++) {
                //adding onclick attribute in all li tag
                allList[i].addEventListener('click', function () {
                    removeThumbnail.style.display = "block";
                    buttonsContainer.classList.add("on-show");

                    select(this);
                })
            }
        } else {
            searchWrapper.classList.remove("active"); //hide autocomplete box
        }
    }

    function select(element) {
        var selectData = element.textContent || element;

        searchField.value = selectData;
        creatorEmail.innerHTML = selectData;
        removeThumbnail.classList.add("active")
        searchWrapper.classList.remove("active");

        // To replace with the creator thumbnail
        ownerAvatar.style.backgroundImage = "url(" + profiles[selectData] + ")";

        removeThumbnail.addEventListener('click', function () {
            this.classList.remove("active");
            creatorEmail.innerHTML = 'No creator assigned';
            searchField.value = '';
            ownerAvatar.style.backgroundImage = "url(/images/yellow-vector.svg)";
            this.style.display = 'none';
            buttonsContainer.classList.remove("on-show");
        });
    }

    function showSuggestions(list) {
        var listData;

        if (!list.length) {
            listData = `<li class="non-cursor"><div class="hide-cursor">No user found try again</div></li>`;
        } else {
            listData = list.join('');
        }

        suggBox.innerHTML = listData;
    }
}());
