@push('styles')
    <link rel="stylesheet" href="{{mix('/css/templates/apps/create.css')}}">
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
    Edit {{$data['display_name']}}
@endsection

@section('content')

    <x-heading heading="Apps" tags="EDIT"></x-heading>

    <div class="content">
        <nav>
            <a href="#" class="nav-item app-details-nav active">
                <span>1</span> App details
            </a>
            <a href="#" class="nav-item select-countries-nav">
                <span>2</span> Select countries
            </a>
            <a href="#" class="nav-item select-products-nav">
                <span>3</span> Select products
            </a>
        </nav>

        <div class="row">

            <form id="form-edit-app">
                <div class="app-details active">
                    @svg('app-avatar', '#ffffff')
                    <div class="group">
                        <label for="name">Name your app *</label>
                        <input type="text" name="name" id="name" placeholder="Enter name" required value="{{ $data['display_name'] }}" maxlength="100">
                    </div>

                    <div class="group">
                        <label for="url">Callback url</label>
                        <input type="url" name="url" id="url" placeholder="Enter callback url (Eg. https://callback.com)" value="{{ $data['callback_url'] }}">
                    </div>

                    <div class="group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="5" placeholder="Enter description">{{ $data['description'] }}</textarea>
                    </div>

                    <div class="form-actions">
                        <button class="dark next">
                            Next
                            @svg('arrow-forward', '#ffffff')
                        </button>
                    </div>
                </div>

                <div class="select-countries">
                    <p>Select the countries you would like to associate with your app *</p>

                    <div class="countries">
                        @foreach($countries as $key => $country)
                            <label class="country" for="country-{{ $loop->index + 1 }}" data-location="{{ $key }}">
                                @svg('$key', '#000000', 'images/locations')
                                <input type="radio" id="country-{{ $loop->index + 1 }}" class="country-checkbox" name="country-checkbox" value="{{ $key }}" @if(isset($data->country->code) && $key === $data->country->code) checked @endif data-location="{{ $key }}" autocomplete="off">
                                <div class="country-checked"></div>
                                {{ $country }}
                            </label>
                        @endforeach
                    </div>

                    <div class="form-actions">
                        <button class="dark outline back">Back</button>
                        <button id="select-products-button" class="dark next">
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
                        @foreach ($products as $category => $products)
                            <div class="category" data-category="{{ $category }}">
                                <h3>{{ $category }}</h3>
                                @foreach ($products as $product)
                                    @php
                                        $tags = array($product->group, $product->category->title);
                                        $class = in_array($product->name, $selectedProducts) ? 'product-block selected' : 'product-block';
                                        $href = route('product.show', $product->slug);
                                    @endphp
                                    <x-card-product :title="$product->display_name"
                                                    :class="$class"
                                                    :href="$href"
                                                    :tags="$tags"
                                                    :addButtonId="$product->slug"
                                                    :data-title="$product->name"
                                                    :data-group="$product->group"
                                                    :data-locations="$product->locations">{{ $product->description ?: 'View the product' }}
                                    </x-card-product>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    <div class="form-actions">
                        <button class="dark outline back">Back</button>
                        <button class="dark" id="update">Update app</button>
                    </div>
                </div>

                @csrf

                @method('PUT')

            </form>
        </div>

        <button type="reset">Cancel</button>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', init);

        var nav = document.querySelector('.content nav');
        var form = document.getElementById('form-edit-app');
        var buttons = document.querySelectorAll('.next');
        var backButtons = document.querySelectorAll('.back');
        var checkedBoxes = document.querySelectorAll('input[name=country-checkbox]:checked');
        var appProducts = document.querySelectorAll('.products .selected .buttons a:last-of-type');
        var hasCountry = {{ +!is_null($data->country_code) }};

        function init() {
            handleButtonClick();
            handleBackButtonClick();
        }

        function handleButtonClick() {
            for (var i = 0; i < buttons.length; i++) {
                buttons[i].addEventListener('click', nextButtonHandler);
            }
        }

        function handleBackButtonClick() {
            for (var j = 0; j < backButtons.length; j++) {
                backButtons[j].addEventListener('click', backButtonHandler);
            }
        }

        function nextButtonHandler(ev) {
            var activeDiv = this.parentNode.parentNode;
            var nextDiv = activeDiv.nextElementSibling;
            var urlValue = document.getElementById('url').value;

            ev.preventDefault();

            if(hasCountry) nextDiv = nextDiv.nextElementSibling;

            if(activeDiv.classList.contains('app-details') && form.elements['name'].value === ''){
                return void addAlert('error', 'Please choose a name for your app.');
            }

            if(urlValue !== '' && !/https?:\/\/.*\..*/.test(urlValue)) {
                return void addAlert('error', ['Please add a valid url', 'Eg. https://callback.com']);
            }

            if(activeDiv.classList.contains('select-countries') && document.querySelectorAll('.country-checkbox:checked').length === 0){
                return void addAlert('error', 'Please select a country.');
            }

            activeDiv.classList.remove('active');
            document.querySelector('.nav-item.active').classList.remove('active');
            document.querySelector('.' + nextDiv.className + '-nav').classList.add('active');

            nextDiv.classList.add('active');
        }

        function backButtonHandler(ev) {
            var activeDiv = this.parentNode.parentNode;
            var previousDiv = activeDiv.previousElementSibling;

            ev.preventDefault();

            if(hasCountry) previousDiv = previousDiv.previousElementSibling;

            activeDiv.classList.remove('active');
            document.querySelector('.nav-item.active').classList.remove('active');
            document.querySelector('.' + previousDiv.className + '-nav').classList.add('active');

            previousDiv.classList.add('active');
        }

        document.querySelector('[type="reset"]').addEventListener('click', function () {
            document.location.href = '/apps';
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

        @if(!is_null($data->country_code))
            filterLocations(['{{ $data->country->code }}']);
            filterProducts(['{{ $data->country->code }}']);
        @endif

        function filterLocations(selected) {

            var locations = document.querySelectorAll('.filtered-countries img');

            for(var i = 0; i < locations.length; i++) {

                locations[i].style.opacity = "0.15";

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
                    }
                }
            }

            return false;
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

        for(var p = 0; p < appProducts.length; ++p) {
            appProducts[p].classList.remove('plus');
            appProducts[p].classList.add('done');
        }

        var update = document.getElementById('form-edit-app').addEventListener('submit', handleUpdate);

        function handleUpdate(event) {
            var elements = this.elements;
            var result;
            var app = {
                display_name: elements['name'].value,
                url: elements['url'].value,
                description: elements['description'].value,
                country: document.querySelector('.country-checkbox:checked').dataset.location,
                products: [],
                _method: 'PUT'
            };
            var button = document.getElementById('update');
            var url = "{{ route('app.update', ['user' => $data->developer_id, 'app' => $data]) }}";
            var xhr = new XMLHttpRequest();
            var selectedProducts = document.querySelectorAll('.products .selected .buttons a:last-of-type');

            event.preventDefault();

            for(i = 0; i < selectedProducts.length; i++) {
                app.products.push(selectedProducts[i].dataset.name);
            }

            if (app.products.length === 0) {
                return void addAlert('error', 'Please select at least one product.')
            }

            button.disabled = true;
            button.textContent = "Updating...";

            xhr.open('POST', url, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.send(JSON.stringify(app));

            xhr.onload = function() {
                if (xhr.status === 200) {
                    addAlert('success', 'Application updated successfully', function(){
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

                    addAlert('error', result.message || 'Sorry there was a problem updating your app. Please try again.');

                    button.removeAttribute('disabled');
                    button.textContent = 'Update';
                }

                document.getElementById('update').textContent = "Update";
            };
        }
    </script>
@endpush
