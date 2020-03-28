@push('styles')
    <link rel="stylesheet" href="/css/templates/apps/_create.css">
@endpush

@extends('layouts.sidebar')

@section('sidebar')
    <x-sidebar-accordion id="sidebar-accordion"
                         :list="
    [ 'Manage' =>
        [
            [ 'label' => 'Profile', 'link' => '#'],
            [ 'label' => 'Approved apps', 'link' => '#'],
            [ 'label' => 'Revoked apps','link' => '#'],
        ],
        'Discover' =>
        [
            [ 'label' => 'Browse all products', 'link' => '#'],
            [ 'label' => 'Working with our products','link' => '#'],
        ]
    ]
    " />
@endsection

@section('content')

    <x-heading heading="Apps" tags="CREATE NEW"></x-heading>

    <div class="container" id="app-create">

        <nav>
            <a href="#" class="active">
                <span>1</span> App details
            </a>
            <a href="#">
                <span>2</span> Select countries
            </a>
            <a href="#">
                <span>3</span> Add products
            </a>
        </nav>

        <div class="row">

            <form id="create-app" action="{{ route('app.store') }}" method="POST">

                <div class="active">
                    @svg('app-avatar', '#ffffff')
                    <div class="group">
                        <label for="name">Name your app *</label>
                        <input type="text" name="name" id="name" placeholder="Enter name" required>
                    </div>

                    <div class="group">
                        <label for="url">Callback url *</label>
                        <input type="url" name="url" id="url" placeholder="Enter callback url" required>
                    </div>

                    <div class="group">
                        <label for="description">Description *</label>
                        <textarea name="description" id="description" rows="5" placeholder="Enter description" required></textarea>
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
                            <label class="country" for="country-{{ $loop->index + 1 }}" data-code="{{ $key }}">
                                @svg('$key', '#000000', 'images/locations')
                                <input type="checkbox" id="country-{{ $loop->index + 1 }}" name="country-checkbox">
                                {{ $country }}
                            </label>
                        @endforeach
                    </div>

                    <div class="form-actions">
                        <button class="dark outline back">Back</button>
                        <button class="dark next">
                            Add products
                            @svg('arrow-forward', '#ffffff')
                        </button>
                    </div>
                </div>

                <div class="select-products">
                    <p>Select the products you would like to add to your app.</p>

                    <p>Showing products for</p>

                    <div class="filtered-countries">
                        @foreach($countries as $key => $country)
                            <img src="/images/locations/{{$key}}.svg" title="{{$country}} flag" alt="{{$country}} flag" data-code="{{ $key }}">
                        @endforeach
                    </div>

                    <div class="products">
                        @foreach ($products as $category=>$products)
                            <div class="category" data-category="{{ $category }}">
                                <h3>{{ $category }}</h3>
                                @foreach ($products as $product)
                                    @php
				                    $tags = array($product->group, $product->category);
                                    @endphp
                                    <x-card-product :title="$product->display_name"
                                                    class="product-block"
                                                    :href="$product->slug"
                                                    :tags="$tags"
                                                    :addButtonId="$product"
                                                    :data-title="$product->display_name"
                                                    :data-group="$product->group"
                                                    :data-locations="$product->locations">{{ !empty($product->description)?$product->description:'View the product' }}
                                    </x-card-product>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    <div class="form-actions">
                        <button class="dark outline back">Back</button>
                        <button class="dark" type="submit">
                            Create app
                        </button>
                    </div>
                </div>

                @csrf

            </form>
        </div>

        <button type="reset">Cancel</button>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', init);

    var nav = document.querySelector('#app-create nav');
    var form = document.querySelector('form');
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

        for (var k = 0; k < els.length; k++) {
            els[k].classList.remove('active');
        }

        nav.querySelector('a').classList.add('active');

        form.reset();
    });

    var countries = document.querySelectorAll('.country');
    for (var l = 0; l < countries.length; l++) {
        countries[l].addEventListener('change', selectCountry);
    }

    function selectCountry(event) {
        var select = event.currentTarget;
        var code = select.dataset.code;

        select.classList.toggle('selected');
        select.querySelector('input[type=checkbox]').classList.toggle('selected');

        console.log(code);

/*        var filteredCountries = document.querySelectorAll('.filtered-countries img');

        console.log(filteredCountries);*/

        // var array = [];
        // var countryCheckBoxes = document.querySelectorAll('input[type=checkbox]:checked');

        // for (var m = 0; m < countryCheckBoxes.length; m++) {
        //     array.push(countryCheckBoxes[m]);
        //
        //     var select = event.currentTarget;
        //     console.log(select);
        //
        //     countryCheckBoxes[m].classList.add('selected');
        //     countryCheckBoxes[m].parentElement.classList.add('selected');
        //
        //     // var unselectedCheckBoxes = document.querySelectorAll('input[type=checkbox]:not(.selected)');
        //     //
        //     // for (var u = 0; u < unselectedCheckBoxes.length; u++) {
        //     //
        //     //     unselectedCheckBoxes[u].classList.add('not-selected');
        //     //     unselectedCheckBoxes[u].parentElement.classList.add('not-selected');
        //     // }
        // }
        //
        // console.log(array);
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

                addProduct(selectedProduct);
            }
        });
    }

    function addProduct(product) {
        var products = document.querySelector('#products');
        var selectedProduct = document.createElement('input');

        selectedProduct.setAttribute('type', 'hidden');
        selectedProduct.setAttribute("name", "selected_product[]");
        selectedProduct.setAttribute("value", product.dataset.title);

        form.append(p);
    }
</script>
@endpush
