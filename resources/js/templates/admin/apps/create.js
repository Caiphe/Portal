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

    document.getElementById('next-app-owner').addEventListener('click', nextAppOwner);
    document.getElementById('next-app-details').addEventListener('click', nextAppDetails);
    document.getElementById('next-select-products').addEventListener('click', nextSelectProducts);
    document.getElementById('next-create-app').addEventListener('click', handleCreate);
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
        var elements = form.elements;
        var urlValue = null;
        var errors = [];

        if (elements['name'].value === '') {
            errors.push({ msg: 'Please add a name for your app', el: elements['name'] });
        } else {
            elements['name'].nextElementSibling.textContent = '';
        }

        urlValue = elements['url'].value;

        if (urlValue !== '' && !/https?:\/\/.*\..*/.test(urlValue)) {
            errors.push({ msg: 'Please add a valid url. Eg. https://callback.com', el: elements['url'] });
        } else {
            elements['url'].nextElementSibling.textContent = '';
        }

        if (errors.length > 0) {
            for (var i = errors.length - 1; i >= 0; i--) {
                errors[i].el.nextElementSibling.textContent = errors[i].msg;
            }

            return;
        }

        next();
    }

    function nextSelectProducts() {
        if (document.querySelectorAll('.country-checkbox:checked').length === 0) {
            return void addAlert('error', 'Please select a country');
        }

        next();
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

    function handleCreate() {
        var elements = form.elements;
        var attrNames = elements['attribute[name][]'];
        var attrValues = elements['attribute[value][]'];

        var selectedProducts = document.querySelectorAll('.add-product:checked');
        var button = document.getElementById('next-create-app');
        var app = {
            app_owner: elements['app-owner'].value,
            display_name: elements['name'].value,
            url: elements['url'].value,
            description: elements['description'].value,
            country: document.querySelector('.country-checkbox:checked').dataset.location,
            products: [],
            attribute: [],
        };

        for (var i = 0; i < selectedProducts.length; i++) {
            app.products.push(selectedProducts[i].value);
        }

        if (app.products.length === 0) {
            return void addAlert('error', 'Please select at least one product.')
        }

        if(attrNames && attrNames.length === undefined) {
            attrNames = [attrNames];
            attrValues = [attrValues];
        }

        if(attrNames){
            for(var i = 0; i < attrNames.length; i++) {
                app.attribute.push({
                    'name': attrNames[i].value,
                    'value': attrValues[i].value
                });
            }
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

            button.removeAttribute('disabled');
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

    // custom attribute add
    var addAttributeBtn = document.querySelector('#add-attribute');
    var attributeName = document.querySelector('#attribute-name');
    var attributeValue = document.querySelector('#attribute-value');
    var attributeErrorMessage = document.querySelector('#attribute-error');
    var attributesList = document.querySelector('#custom-attributes-list');

    addAttributeBtn.addEventListener('click', addNewAttribute);

    function addNewAttribute(){
        if(attributeName.value === "" || attributeValue.value === ''){
            attributeErrorMessage.classList.add('show');
            return;
        }

        attributeErrorMessage.classList.remove('show');
        var customAttributeBlock = document.getElementById('custom-attribute').innerHTML;
        customAttributeBlock = document.createRange().createContextualFragment(customAttributeBlock);
        customAttributeBlock.querySelector('.name').value = attributeName.value;
        customAttributeBlock.querySelector('.value').value = attributeValue.value;
        // customAttributeBlock.querySelector('.attribute-remove-btn').addEventListener('click', attributeRemove);
        attributesList.appendChild(customAttributeBlock);
        document.querySelector('.no-attribute').classList.add('hide');
        document.querySelector('.attributes-heading').classList.add('show');
        attributeName.value = '';
        attributeValue.value = '';
    }

    function attributeRemove(){
        var attribute = this.parentNode;
        attribute.parentNode.removeChild(attribute);

        if(document.querySelectorAll('.each-attribute-block').length === 0){
            document.querySelector('.no-attribute').classList.remove('hide');
            document.querySelector('.attributes-heading').classList.remove('show');
        }
    }
}());
