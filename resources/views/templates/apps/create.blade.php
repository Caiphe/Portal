@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/apps/create.css') }}">
@endpush

@extends('layouts.sidebar')

@section('sidebar')
    <x-sidebar-accordion id="sidebar-accordion" active="/apps" :list="
    [ 'Manage' =>
        [
            [ 'label' => 'My profile', 'link' => '/profile'],
            [ 'label' => 'My apps', 'link' => '/apps'],
            [ 'label' => 'My teams', 'link' => '/teams'],
        ],
        'Discover' =>
        [
            [ 'label' => 'Browse all products', 'link' => '/products'],
            [ 'label' => 'Working with our products','link' => '/getting-started'],
        ]
    ]
    " />
@endsection

@section('title')
    Create app
@endsection

@section('content')

    <x-heading heading="Apps" tags="CREATE NEW"></x-heading>

    <x-twofa-warning class="tall"></x-twofa-warning>

    <div class="content">

        <nav>
            <a href="#" class="active">
                <span>1</span> App details
            </a>
            <a href="#">
                <span>2</span> Select countries
            </a>
            <a href="#">
                <span>3</span> Select products
            </a>
        </nav>

        <form id="form-create-app">

            <div class="active">
                <div class="user-thumbnails">
                    <div class="thumbail" style="background-image: url({{ $user->profile_picture }})"></div>
                    <label for="user-thumb">
                        <input type="file" name="user-thumb" class="user-thumb">
                    </label>
                </div>
                {{-- @svg('app-avatar', '#ffffff') --}}

                <div class="groups">
                    <div class="group">
                        <label for="name">Name your app *</label>
                        <input type="text" name="name" id="name" placeholder="Enter name" maxlength="100" autocomplete="off" required>
                        <div class="error">{{ isset($error) && $error->get('display_name', '') }}</div>
                    </div>

                    <div class="group group-info">
                        <label for="url">Callback url @svg('info-icon', '#a5a5a5')<small class="tooltip">The callback URL typically specifies the URL of an app that is designated to receive an authorization code on behalf of the client app. In addition, this URL string is used for validation. A callback URL is required only for 3-legged Oauth</small></label>
                        <input type="url" name="url" id="url" placeholder="Enter callback url (eg. https://callback.com)" autocomplete="off">
                        <div class="error">{{ isset($error) && $error->get('url', '') }}</div>
                    </div>

                    <div class="group group-info team-field">
                        <label for="team">Select team *</label>
                        <div class="select_wrap">
                            <input name="team" id="team" class="selected-data" value="">
                            <ul class="default_option">
                                <li>
                                    {{-- <div class="option"> --}}
                                        <div class="select-default">Please select team <span class="hide-mobi">to publish under</span></div>
                                    {{-- </div> --}}
                                </li>
                            </ul>

                            <ul class="select_ul">
                                <li>
                                    <div class="option">
                                        <div class="icon" style="background-image: url({{ $user->profile_picture }})"></div>
                                        <div class="select-data" data-createdby="" data-teamid="">{{ $user->full_name }} (You)</div>
                                    </div>
                                </li>
                                @foreach($teams as $team)
                                <li>
                                    <div class="option">
                                        <div class="icon" style="background-image: url({{ $team['logo'] }})"></div>
                                        <div class="select-data" data-createdby="{{ $user->email }}" data-teamid="{{ $team['id'] }}">{{ $team['name'] }}</div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="error">{{ isset($error) && $error->get('team', '') }}</div>
                    </div>

                    <div class="group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="5" placeholder="Enter description"></textarea>
                        <div class="error">{{ isset($error) && $error->get('description', '') }}</div>
                    </div>
                </div>

                <button class="dark next">
                    Select countries
                    @svg('arrow-forward', '#ffffff')
                </button>
            </div>

            <div class="select-countries">
                <p>Select the countries you would like to associate with your app *</p>

                <div class="countries">
                    @foreach($countries as $key => $country)
                        <label class="country" for="country-{{ $loop->index + 1 }}" data-location="{{ $key }}">
                            @svg('$key', '#000000', 'images/locations')
                            <input type="radio" id="country-{{ $loop->index + 1 }}" class="country-checkbox" name="country-checkbox" value="{{ $key }}" data-location="{{ $key }}" autocomplete="off">
                            <div class="country-checked"></div>
                            {{ $country }}
                        </label>
                    @endforeach
                </div>

                <div class="form-actions">
                    <button class="dark outline back">Back</button>
                    <button class="dark next" id="select-products-button">
                        Select products
                        @svg('arrow-forward', '#ffffff')
                    </button>
                </div>
            </div>

            <div class="select-products">
                <p>Select the products you would like to add to your app.</p>

                <p>Showing products for</p>

                <div class="filtered-countries">
                    @foreach($countries as $key => $country)
                        <img src="/images/locations/{{$key}}.svg" title="{{$country}} flag" alt="{{$country}} flag" data-location="{{ $key }}">
                    @endforeach
                </div>

                <div class="products">
                    @foreach ($products as $category => $prods)
                        <div class="category" data-category="{{ $category }}">
                            <h3 class="category-heading" data-category="{{ $prods[0]->category_cid }}">{{ $category }}</h3>
                            @foreach ($prods as $product)
                                <x-card-product :title="$product->display_name"
                                                class="product-block"
                                                :href="route('product.show', $product->slug)"
                                                target="_blank"
                                                :tags="[$product->group, $product->category->title]"
                                                :addButtonId="$product->slug"
                                                :data-title="$product->name"
                                                :data-group="$product->group"
                                                :data-access="$product->access"
                                                :data-category="$product->category_cid"
                                                :data-locations="$product->locations">{{ !empty($product->description)?$product->description:'View the product' }}
                                </x-card-product>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <div class="form-actions">
                    <button class="dark outline back">Back</button>
                    <button class="dark" id="create">
                        Create app
                    </button>
                </div>
            </div>
        </form>
        <button type="reset">Cancel</button>
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
    var default_option = document.querySelector('.default_option');
    var select_wrap = document.querySelector('.select_wrap');
    var inputData = document.querySelector('.selected-data');
    var submit = document.getElementById('form-create-app').addEventListener('submit', handleCreate);
    var select_ul = document.querySelectorAll('.select_ul li');

    function init() {
        handleButtonClick();
        handleBackButtonClick();
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
        var elements = form.elements;
        for (var i = 0; i < buttons.length; i++) {

            buttons[i].addEventListener('click', function (event) {
                var errors = [];
                var urlValue = elements['url'].value;
                event.preventDefault();

                if(form.firstElementChild.classList.contains('active')) {
                    if(elements['name'].value === '') {
                        errors.push({msg: 'Please add a name for your app', el: elements['name']});
                    } else {
                        elements['name'].nextElementSibling.textContent = '';
                    }

                    if(urlValue !== '' && !/https?:\/\/.*\..*/.test(urlValue)) {
                        errors.push({msg: 'Please add a valid url. Eg. https://callback.com', el: elements['url']});
                    } else {
                        elements['url'].nextElementSibling.textContent = '';
                    }

                    if(errors.length > 0){
                        for (var i = errors.length - 1; i >= 0; i--) {
                            errors[i].el.nextElementSibling.textContent = errors[i].msg;
                        }

                        return;
                    }

                    nav.querySelector('a').nextElementSibling.classList.add('active');

                    form.firstElementChild.classList.remove('active');
                    form.firstElementChild.style.display = 'none';
                    form.firstElementChild.nextElementSibling.classList.add('active');

                } else if (form.firstElementChild.nextElementSibling.classList.contains('active')) {
                    if(document.querySelectorAll('.country-checkbox:checked').length === 0) {
                        return void addAlert('error', 'Please select a country');
                    }

                    nav.querySelector('a').nextElementSibling.nextElementSibling.classList.add('active');

                    form.firstElementChild.nextElementSibling.classList.remove('active');
                    form.firstElementChild.nextElementSibling.nextElementSibling.classList.add('active');
                }
            });
        }
    }

    function handleBackButtonClick() {
        var selectedProducts = null;

        for (var j = 0; j < backButtons.length; j++) {

            backButtons[j].addEventListener('click', function (event) {
                event.preventDefault();

                if(form.firstElementChild.nextElementSibling.classList.contains('active')) {
                    form.firstElementChild.nextElementSibling.classList.remove('active');
                    form.firstElementChild.classList.add('active');
                    form.firstElementChild.style.display = 'flex';

                    nav.firstElementChild.nextElementSibling.classList.remove('active');

                } else if(form.firstElementChild.nextElementSibling.nextElementSibling.classList.contains('active')) {
                    form.firstElementChild.nextElementSibling.nextElementSibling.classList.remove('active');
                    form.firstElementChild.nextElementSibling.classList.add('active');

                    nav.firstElementChild.nextElementSibling.nextElementSibling.classList.remove('active');

                    selectedProducts = document.querySelectorAll('.add-product:checked');
                    for (var i = selectedProducts.length - 1; i >= 0; i--) {
                        selectedProducts[i].checked = false;
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
        var buttons = document.querySelectorAll('.add-product:checked');

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
            buttons[w].checked = false;
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
        var categoryHeadings = document.querySelectorAll(".category-heading");
        var showCategories = [];

        for (var i = products.length - 1; i >= 0; i--) {
            products[i].style.display = "none";

            var locations =
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
        var url = "{{ route('app.store') }}";
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
                });
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
            }
        };
    }
</script>
@endpush
