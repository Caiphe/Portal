@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/apps/create.css') }}">
@endpush

@extends('layouts.sidebar')

@section('sidebar')
    <x-sidebar-accordion id="sidebar-accordion" active="/apps" :list="
    [ 'Manage' =>
        [
            [ 'label' => 'Profile', 'link' => '/profile'],
            [ 'label' => 'My apps', 'link' => '/apps'],
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
                @svg('app-avatar', '#ffffff')
                <div class="group">
                    <label for="name">Name your app *</label>
                    <input type="text" name="name" id="name" placeholder="Enter name" required>
                </div>

                <div class="group">
                    <label for="url">Callback url</label>
                    <input type="url" name="url" id="url" placeholder="Enter callback url">
                </div>

                <div class="group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="5" placeholder="Enter description"></textarea>
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
                            <input type="radio" id="country-{{ $loop->index + 1 }}" class="country-checkbox" name="country-checkbox" value="{{ $key }}" data-location="{{ $key }}">
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

    function init() {
        handleButtonClick();
        handleBackButtonClick();
        clearCheckBoxes();
    }

    function handleButtonClick() {
        for (var i = 0; i < buttons.length; i++) {

            buttons[i].addEventListener('click', function (event) {
                event.preventDefault();
                if(form.firstElementChild.classList.contains('active')) {

                    nav.querySelector('a').nextElementSibling.classList.add('active');

                    form.firstElementChild.classList.remove('active');
                    form.firstElementChild.style.display = 'none';
                    form.firstElementChild.nextElementSibling.classList.add('active');

                } else if (form.firstElementChild.nextElementSibling.classList.contains('active')) {
                    nav.querySelector('a').nextElementSibling.nextElementSibling.classList.add('active');

                    form.firstElementChild.nextElementSibling.classList.remove('active');
                    form.firstElementChild.nextElementSibling.nextElementSibling.classList.add('active');
                }
            });
        }
    }

    function handleBackButtonClick() {
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
        var selected = [countryRadioBoxes.dataset.location];

        filterLocations(selected);
        filterProducts(selected);

        document.getElementById('select-products-button').click();
    }

    function filterLocations(selected) {

        var locations = document.querySelectorAll('.filtered-countries img');

        for(var i = 0; i < locations.length; i++) {

            locations[i].style.opacity = "0.5";

            for(var j = 0; j < selected.length; j++) {

                if (locations[i].dataset.location === selected[j]) {
                    locations[i].style.opacity = "1";
                }
            }
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

            if(selectedCountry.length === 0 || inSelectedArray(selectedCountry, locations)) {

                products[i].style.display = "flex";
            }
        }
    }

    function inSelectedArray(selectedCountry, locations) {
        for (var j = 0; j < selectedCountry.length; j++) {
            for (var k = 0; k < locations.length; k++) {
                if(selectedCountry[j] === locations[k] || locations[k] === 'all') {
                    return true;
                } else {
                    return false;
                }
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

    var submit = document.getElementById('create').addEventListener('click', handleCreate);

    function handleCreate(event) {
        event.preventDefault();

        var button = document.getElementById('create');
        button.disabled = true;
        button.textContent = 'Creating...';

        var app = {
            name: document.querySelector('#name').value,
            url: document.querySelector('#url').value,
            description: document.querySelector('#description').value,
            country: document.querySelector('.country-checkbox:checked').dataset.location,
            products: []
        };

        var selectedProducts = document.querySelectorAll('.products .selected .buttons a:last-of-type');

        var products = [];
        for(i = 0; i < selectedProducts.length; i++) {
            products.push(selectedProducts[i].dataset.name);
        }

        app.products = products;

        var url = "{{ route('app.store') }}";
        var xhr = new XMLHttpRequest();

        xhr.open('POST', url);
        xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.send(JSON.stringify(app));

        xhr.onload = function() {
            window.scrollTo({
                top: 0,
                left: 0,
                behavior: 'smooth'
            });

            if (xhr.status === 200) {

                addAlert('success', 'Application created successfully', function(){
                    window.location.href = "{{ route('app.index') }}";
                });
            } else {
                result = xhr.responseText ? JSON.parse(xhr.responseText) : null;
                addAlert('error', result.message || 'Sorry there was a problem creating your app. Please try again.');

                button.removeAttributer('disabled');
                button.textContent = 'Create';
            }
        };
    }
</script>
@endpush
