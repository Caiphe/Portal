@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/apps/create.css') }}">
@endpush

@extends('layouts.admin')

@section('title')
    Create app
@endsection

@section('content')

    {{-- <x-heading heading="Apps" tags="CREATE NEW"></x-heading> --}}

    <x-twofa-warning class="tall"></x-twofa-warning>

    <div class="content">

        <nav>
            <a href="#" class="active">
                <span>1</span> App owner
            </a>
            <a href="#" class="@if(isset($chosenUser)) active @endif">
                <span>2</span> App details
            </a>
            <a href="#" class="">
                <span>3</span> Select countries
            </a>
            <a href="#" class="">
                <span>4</span> Select products
            </a>
            <a href="#" class="">
                <span>5</span> Complete
            </a>
        </nav>

        <form id="form-create-app">

            <div class="app-owner-container @if(!isset($chosenUser)) active @endif">
                <span class="apps-top-text">Create a new app</span>
                <h1 class="app-create-heading">App owner</h1>
                <span class="gray-text">Modify or continue with the assigned app creator</span>
                <div class="owner-avatar-container">
                    <div class="owner-avatar"></div>
                </div>

                <span class="gray-text">Assigned app creator</span>
                <div class="creator-email"> No creator assigned</div>

                <div class="group owners-list-container">
                    <div class="wrapper">
                        <div class="search-input">
                            <input type="text" class="searchField" name="app-owner" value="{{ $chosenUser->email ?? '' }}" placeholder="Search for email address..." autocomplete="off">
                            <div class="autocom-box">
                                <!-- here list are inserted from javascript -->
                            </div>
                        </div>
                    </div>

                    <div class="apps-button-container">
                        <button type="button" class="btn dark outline" id="assign-to-me">Assign to me</button>
                        <button type="button" class="btn dark outline remove-thumbnail" id="remove-assignee">Remove assignee</button>
                    </div>
                </div>

                <div class="actions-btn-container">
                    <button class="btn dark outline" type="reset">Cancel</button>
                    <button class="btn dark next" type="button">Next</button>
                </div>
            </div>
            <div class="app-details-step @if(isset($chosenUser)) active @endif" >
                {{-- @svg('app-avatar', '#ffffff') --}}
                {{-- Section Heading --}}
                <div class="apps-heading-container">
                    <span class="apps-top-text">Create a new app</span>
                    <h1 class="app-create-heading">App details</h1>
                    <span class="gray-text">Edit basic details of the app</span>
                </div>
                {{-- Section Heading ends --}}

                <div class="group">
                    <label for="name">Name your app *</label>
                    <input type="text" name="name" id="name" placeholder="Enter name" maxlength="100" required>
                </div>

                <div class="group">
                    <label for="url">Callback url</label>
                    <input type="url" name="url" id="url" placeholder="Enter callback url (eg. https://callback.com)">
                </div>

                <div class="group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="5" placeholder="Enter description"></textarea>
                </div>
                <button class="dark next apps-create-btn">Select countries</button>
            </div>

            <div class="select-countries ">
                <div class="apps-heading-container">
                    <span class="apps-top-text">Create a new app</span>
                    <h1 class="app-create-heading">Select countries</h1>
                    <span class="gray-text">Select the countries you would like to associate with your app *</span>
                </div>

                <div class="countries">
                    @foreach($countries as $key => $country)
                        <label class="country" for="country-{{ $loop->index + 1 }}" data-location="{{ $key }}">
                            @svg('$key', '#000000', 'images/locations')
                            <input type="radio" id="country-{{ $loop->index + 1 }}" class="country-checkbox" name="country-checkbox" value="{{ $key }}" data-location="{{ $key }}">
                            <div class="country-checked"></div>
                            {{ $country }}
                        </label>
                    @endforeach
                </div>

                <div class="actions-btn-container">
                    <button class="btn dark outline back">Back</button>
                    <button class="btn dark next" type="button"  id="select-products-button"> Select products</button>
                </div>

                {{-- <div class="form-actions">
                    <button class="dark outline back">Back</button>
                    <button class="dark next" id="select-products-button">
                        Select products
                        @svg('arrow-forward', '#ffffff')
                    </button>
                </div> --}}
            </div>

            <div class="select-products">
                <div class="apps-heading-container">
                    <span class="apps-top-text">Create a new app</span>
                    <h1 class="app-create-heading">Select countries</h1>
                    <span class="gray-text">Select the products you would like to add to your app.</span>
                </div>

                <p class="showing-product-for">SHOWING PRODUCTS FOR</p>
                <div class="filtered-countries">
                    @foreach($countries as $key => $country)
                        <img src="/images/locations/{{$key}}.svg" title="{{$country}} flag" alt="{{$country}} flag" data-location="{{ $key }}">
                    @endforeach
                </div>

                <div class="products">
                    @foreach ($products as $category=>$products)
                        <div class="category" data-category="{{ $category }}">
                            <h3>{{ $category }}</h3>
                            @foreach ($products as $product)
                                @php
                                    $tags = array($product->group, $product->category->title);
                                    $href = route('product.show', $product->slug);
                                @endphp
                                <x-card-product :title="$product->display_name"
                                                class="product-block"
                                                :href="$href"
                                                target="_blank"
                                                :tags="$tags"
                                                :addButtonId="$product->slug"
                                                :data-title="$product->name"
                                                :data-group="$product->group"
                                                :data-locations="$product->locations">{{ !empty($product->description)?$product->description:'View the product' }}
                                </x-card-product>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <div class="actions-btn-container">
                    <button class="btn dark outline back">Back</button>
                    <button class="btn dark next" id="create">Next</button>
                </div>
            </div>

            <div class="complete-container">
                <div class="apps-heading-container">
                    <span class="apps-top-text">Create a new app</span>
                    <h1 class="app-create-heading">Complete</h1>
                    @svg('check-complete', '#FFCC00')
                </div>

                <div class="success-message-container">
                    <h2 class="success-message">Application created successfully</h2>

                    <a class="back-btn" href="{{ route('admin.dashboard.index') }}">Back to dashboard</a>
                </div>
            </div>

        </form>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', init);

        var nav = document.querySelector('.content nav');
        var form = document.getElementById('form-create-app');
        var buttons = document.querySelectorAll('.next');
        var backButtons = document.querySelectorAll('.back');
        var checkedBoxes = document.querySelectorAll('input[name=country-checkbox]:checked');

        var appCreatorEmail = "{{ $appCreatorEmail }}";

        var suggestions = @json($userEmails);

        var profiles = @json($userProfiles);

        // This will take the creator thumbnail
        var creatorThumb = '/images/user-thumbnail.jpg';

        var searchWrapper = document.querySelector(".search-input");
        var inputBox = searchWrapper.querySelector(".searchField");
        var suggBox = searchWrapper.querySelector(".autocom-box");
        var creatorEmail = document.querySelector(".creator-email");
        var removeThumbnail = document.getElementById('remove-assignee');
        var ownerAvatar = document.querySelector(".owner-avatar");

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

                    if(form.firstElementChild.classList.contains('active')) {

                        if(inputBox.value === '') {
                            return void addAlert('error', 'Please add a valid email');
                        }

                        nav.querySelector('a').nextElementSibling.classList.add('active');

                        form.firstElementChild.classList.remove('active');
                        form.firstElementChild.style.display = 'none';
                        form.firstElementChild.nextElementSibling.classList.add('active');

                    } else if (form.firstElementChild.nextElementSibling.classList.contains('active')) {
                        if(document.getElementById('name').value === '') {
                            return void addAlert('error', 'Please add a name for your app');
                        }

                        if(urlValue !== '' && !/https?:\/\/.*\..*/.test(urlValue)) {
                            return void addAlert('error', ['Please add a valid url', 'Eg. https://callback.com']);
                        }

                        nav.querySelector('a').nextElementSibling.nextElementSibling.classList.add('active');

                        form.firstElementChild.nextElementSibling.classList.remove('active');
                        form.firstElementChild.nextElementSibling.style.display = 'none';
                        form.firstElementChild.nextElementSibling.nextElementSibling.classList.add('active');
                    } else if (form.firstElementChild.nextElementSibling.nextElementSibling.classList.contains('active')) {
                        if(document.querySelectorAll('.country-checkbox:checked').length === 0) {
                            return void addAlert('error', 'Please select a country');
                        }

                        nav.querySelector('a').nextElementSibling.nextElementSibling.nextElementSibling.classList.add('active');

                        form.firstElementChild.nextElementSibling.nextElementSibling.classList.remove('active');
                        form.firstElementChild.nextElementSibling.nextElementSibling.nextElementSibling.classList.add('active');
                    } else {
                        if(document.querySelectorAll('.products .selected .buttons .done').length === 0) {
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

                    if(activeSectionClass === 'select-countries active') {
                        var countryCheckboxChecked = document.querySelector('.country-checkbox:checked');

                        activeSection.classList.remove('active');
                        activeSection.previousElementSibling.classList.add('active');
                        activeSection.previousElementSibling.style.display = 'flex';

                        activeNav.classList.remove('active');

                        if (countryCheckboxChecked) {
                            countryCheckboxChecked.checked = false;
                        }

                    } else if(activeSectionClass === 'select-products active') {
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
            if(form.querySelector('.active') !== form.firstElementChild) {
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

            for(var x = 0; x < countries.length; x++) {
                countries[x].classList.remove('selected');
            }

            for(var z = 0; z < products.length; z++) {
                products[z].classList.remove('selected');
            }

            for(var w = 0; w < buttons.length; w++) {
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

        function selectCountry(event) {
            var countryRadioBoxes = document.querySelectorAll('.country-checkbox:checked')[0];
            var selected = countryRadioBoxes.dataset.location;

            filterLocations(selected);
            filterProducts(selected);

            document.getElementById('select-products-button').click();
        }

        function filterLocations(selected) {
            var locations = document.querySelectorAll('.filtered-countries img');

            for(var i = 0; i < locations.length; i++) {
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

                if(locations[0] === 'all' || locations.indexOf(selectedCountry) !== -1){
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

                if(document.querySelectorAll('[data-title] button')) {
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

            for(i = 0; i < selectedProducts.length; i++) {
                app.products.push(selectedProducts[i].dataset.name);
            }

            if (app.products.length === 0) {
                return void addAlert('error', 'Please select at least one product.')
            }

            button.disabled = true;
            button.textContent = 'Creating...';

            var url = "{{ route('app.store') }}";
            var xhr = new XMLHttpRequest();

            xhr.open('POST', url);
            xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.send(JSON.stringify(app));

            xhr.onload = function() {
                if (xhr.status === 200) {
                    nav.querySelector('a').nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.classList.add('active');

                    form.firstElementChild.nextElementSibling.nextElementSibling.nextElementSibling.classList.remove('active');
                    form.firstElementChild.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.classList.add('active');
                } else {
                    var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

                    if(result.errors) {
                        result.message = [];
                        for(var error in result.errors){
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
        inputBox.onkeyup = (e)=>{
            let userData = e.target.value; //user enetered data
            let emptyArray = [];
            if(userData){
                emptyArray = suggestions.filter((data)=>{
                    //filtering array value and user characters to lowercase and return only those words which are start with user enetered chars
                    return data.toLocaleLowerCase().startsWith(userData.toLocaleLowerCase());
                });
                emptyArray = emptyArray.map((data)=>{
                    // passing return data inside li tag
                    return data = `<li>${data}</li>`;
                });
                searchWrapper.classList.add("active"); //show autocomplete box
                showSuggestions(emptyArray);
                let allList = suggBox.querySelectorAll("li");
                for (let i = 0; i < allList.length; i++) {
                    //adding onclick attribute in all li tag
                    allList[i].setAttribute("onclick", "select(this)");
                }
            }else{
                searchWrapper.classList.remove("active"); //hide autocomplete box
            }
        }

        function select(element){
            let selectData = element.textContent || element;

            inputBox.value = selectData;
            creatorEmail.innerHTML = selectData;
            removeThumbnail.classList.add("active")
            searchWrapper.classList.remove("active");

            // To replace with the creator thumbnail
            ownerAvatar.style.backgroundImage = "url(" + profiles[selectData] + ")";

            removeThumbnail.addEventListener('click', function(){
                this.classList.remove("active");
                creatorEmail.innerHTML = 'No creator assigned';
                inputBox.value = '';
                ownerAvatar.style.backgroundImage = "url(/images/yellow-vector.svg)";
                this.style.display = 'none';
            });
        }

        function showSuggestions(list){
            let listData;
            if(!list.length){
                // userValue = inputBox.value;
                listData = `<li class="non-cursor"><div class="hide-cursor">No user found try again</div></li>`;
            }else{
                listData = list.join('');
            }
            suggBox.innerHTML = listData;
        }

    </script>
@endpush
