(function () {
    document.addEventListener('DOMContentLoaded', init);

    var nav = document.querySelector('.content nav');
    var form = document.getElementById('form-create-app');
    var buttons = document.querySelectorAll('.next');
    var backButtons = document.querySelectorAll('.back');
    var checkedBoxes = document.querySelectorAll('input[name=country-checkbox]:checked');
    var appCreatorEmail = adminAppsCreateLookup('appCreatorEmail');
    var suggestions = adminAppsCreateLookup('userEmails');
    var profiles = adminAppsCreateLookup('userProfiles');
    var searchWrapper = document.querySelector(".search-input");
    var inputBox = searchWrapper.querySelector(".searchField");
    var suggBox = searchWrapper.querySelector(".autocom-box");
    var creatorEmail = document.querySelector(".creator-email");
    var removeThumbnail = document.getElementById('remove-assignee');
    var ownerAvatar = document.querySelector(".owner-avatar");
    var buttonsContainer = document.querySelector(".apps-button-container");

    function init() {
        handleButtonClick();
        handleBackButtonClick();
        clearCheckBoxes();
    }

    document.getElementById('assign-to-me').addEventListener('click', assignToMe);

    removeThumbnail.style.display = 'none';

    function assignToMe() {
        select(appCreatorEmail);
        removeThumbnail.style.display = '';
    }

    function handleButtonClick() {
        for (var i = 0; i < buttons.length; i++) {

            buttons[i].addEventListener('click', function (event) {
                var urlValue = document.getElementById('url').value;
                event.preventDefault();

                if (form.firstElementChild.classList.contains('active')) {

                    if (inputBox.value === '') {
                        return void addAlert('error', 'Please add a valid email');
                    }

                    nav.querySelector('a').nextElementSibling.classList.add('active');

                    form.firstElementChild.classList.remove('active');
                    form.firstElementChild.style.display = 'none';
                    form.firstElementChild.nextElementSibling.classList.add('active');

                } else if (form.firstElementChild.nextElementSibling.classList.contains('active')) {
                    if (document.getElementById('name').value === '') {
                        return void addAlert('error', 'Please add a name for your app');
                    }

                    if (urlValue !== '' && !/https?:\/\/.*\..*/.test(urlValue)) {
                        return void addAlert('error', ['Please add a valid url', 'Eg. https://callback.com']);
                    }

                    nav.querySelector('a').nextElementSibling.nextElementSibling.classList.add('active');

                    form.firstElementChild.nextElementSibling.classList.remove('active');
                    form.firstElementChild.nextElementSibling.style.display = 'none';
                    form.firstElementChild.nextElementSibling.nextElementSibling.classList.add('active');
                } else if (form.firstElementChild.nextElementSibling.nextElementSibling.classList.contains('active')) {
                    if (document.querySelectorAll('.country-checkbox:checked').length === 0) {
                        return void addAlert('error', 'Please select a country');
                    }

                    nav.querySelector('a').nextElementSibling.nextElementSibling.nextElementSibling.classList.add('active');

                    form.firstElementChild.nextElementSibling.nextElementSibling.classList.remove('active');
                    form.firstElementChild.nextElementSibling.nextElementSibling.nextElementSibling.classList.add('active');
                } else {
                    if (document.querySelectorAll('.products .selected .buttons .done').length === 0) {
                        return void addAlert('error', 'Please select at least one product');
                    }
                    handleCreate();
                }
            });
        }
    }

    function handleBackButtonClick() {
        var selectedProducts = null;

        for (var j = 0; j < backButtons.length; j++) {

            backButtons[j].addEventListener('click', function (event) {
                var activeSection = document.querySelector('#form-create-app > .active');
                var activeSectionClass = activeSection.className;

                var activeNav = nav.querySelectorAll('.active');
                activeNav = activeNav[activeNav.length - 1];

                event.preventDefault();

                if (activeSectionClass === 'select-countries active') {
                    var countryCheckboxChecked = document.querySelector('.country-checkbox:checked');

                    activeSection.classList.remove('active');
                    activeSection.previousElementSibling.classList.add('active');
                    activeSection.previousElementSibling.style.display = 'flex';

                    activeNav.classList.remove('active');

                    if (countryCheckboxChecked) {
                        countryCheckboxChecked.checked = false;
                    }

                } else if (activeSectionClass === 'select-products active') {
                    activeSection.classList.remove('active');
                    activeSection.previousElementSibling.classList.add('active');

                    activeNav.classList.remove('active');

                    selectedProducts = document.querySelectorAll('.products .selected .buttons .done');

                    for (var i = selectedProducts.length - 1; i >= 0; i--) {
                        selectedProducts[i].classList.toggle('done');
                        selectedProducts[i].classList.toggle('plus');
                        selectedProducts[i].parentNode.parentNode.classList.remove('selected');
                    }
                }
            });
        }
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

        nav.querySelector('a').classList.add('active');

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

        document.getElementById('select-products-button').click();
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

        for (var i = products.length - 1; i >= 0; i--) {
            products[i].style.display = "none";

            var locations =
                products[i].dataset.locations !== undefined
                    ? products[i].dataset.locations.split(",")
                    : ["all"];

            if (locations[0] === 'all' || locations.indexOf(selectedCountry) !== -1) {
                products[i].style.display = "flex";
            }
        }
    }

    /**
     *  Clear checkboxes on page load, otherwise some checkboxes persist checked state.
     */
    function clearCheckBoxes() {
        for (var n = 0; n < checkedBoxes.length; ++n) {
            checkedBoxes[n].checked = false;
        }
    }

    var addProductButtons = document.querySelectorAll('[data-title] a');
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

    //var submit = document.getElementById('form-create-app').addEventListener('submit', handleCreate);

    function handleCreate() {
        var elements = form.elements;
        var app = {
            app_owner: elements['app-owner'].value,
            display_name: elements['name'].value,
            url: elements['url'].value,
            description: elements['description'].value,
            country: document.querySelector('.country-checkbox:checked').dataset.location,
            products: [],
        };

        var selectedProducts = document.querySelectorAll('.products .selected .buttons a:last-of-type');

        var button = document.getElementById('create');

        for (i = 0; i < selectedProducts.length; i++) {
            app.products.push(selectedProducts[i].dataset.name);
        }

        if (app.products.length === 0) {
            return void addAlert('error', 'Please select at least one product.')
        }

        button.disabled = true;
        button.textContent = 'Creating...';

        var url = adminAppsCreateLookup('appStoreUrl');
        var xhr = new XMLHttpRequest();

        xhr.open('POST', url);
        xhr.setRequestHeader('X-CSRF-TOKEN', adminAppsCreateLookup('csrfToken'));
        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.send(JSON.stringify(app));

        xhr.onload = function () {
            if (xhr.status === 200) {
                nav.querySelector('a').nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.classList.add('active');

                form.firstElementChild.nextElementSibling.nextElementSibling.nextElementSibling.classList.remove('active');
                form.firstElementChild.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.classList.add('active');
            } else {
                var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

                if (result.errors) {
                    result.message = [];
                    for (var error in result.errors) {
                        result.message.push(result.errors[error]);
                    }
                }

                addAlert('error', result.message || 'Sorry there was a problem creating your app. Please try again.');

                button.removeAttribute('disabled');
                button.textContent = 'Create';
            }
        };
    }

    // if user press any key and release
    inputBox.onkeyup = (e) => {
        let userData = e.target.value; //user enetered data
        let emptyArray = [];
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
            let allList = suggBox.querySelectorAll("li");
            for (let i = 0; i < allList.length; i++) {
                //adding onclick attribute in all li tag
                allList[i].setAttribute("onclick", "select(this)");
                allList[i].addEventListener('click', function () {
                    removeThumbnail.style.display = "block";
                    buttonsContainer.classList.add("on-show");
                })
            }
        } else {
            searchWrapper.classList.remove("active"); //hide autocomplete box
        }
    }

    function select(element) {
        let selectData = element.textContent || element;

        inputBox.value = selectData;
        creatorEmail.innerHTML = selectData;
        removeThumbnail.classList.add("active")
        searchWrapper.classList.remove("active");

        // To replace with the creator thumbnail
        ownerAvatar.style.backgroundImage = "url(" + profiles[selectData] + ")";

        removeThumbnail.addEventListener('click', function () {
            this.classList.remove("active");
            creatorEmail.innerHTML = 'No creator assigned';
            inputBox.value = '';
            ownerAvatar.style.backgroundImage = "url(/images/yellow-vector.svg)";
            this.style.display = 'none';
            buttonsContainer.classList.remove("on-show");
        });
    }

    function showSuggestions(list) {
        let listData;
        if (!list.length) {
            // userValue = inputBox.value;
            listData = `<li class="non-cursor"><div class="hide-cursor">No user found try again</div></li>`;
        } else {
            listData = list.join('');
        }
        suggBox.innerHTML = listData;
    }
}());