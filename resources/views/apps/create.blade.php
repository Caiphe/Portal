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
                <span>2</span> Select regions
            </a>
            <a href="#">
                <span>3</span> Add products
            </a>
        </nav>

        <div class="row">

            <form id="create-app" action="">

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
                        Select regions
                        @svg('arrow-forward', '#ffffff')
                    </button>
                </div>

                <div>
                    <p>Select the regions you would like to associate with your app *</p>

                    <div class="countries">
                        @foreach($countries as $country)
                            <label class="country" for="country-{{ $loop->index + 1 }}">
                                @svg('za', '#ffffff', 'images/locations')
                                <input type="checkbox" class="country-{{ $loop->index + 1 }}" value="">
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

                <div>
                    <p>Select the products you would like to add to your app.</p>

                    <div class="products">
                        @foreach ($products as $category=>$products)
                            <div class="category" data-category="{{ $category }}">
                                <h3>{{ $category }}</h3>
                                @foreach ($products as $product)
                                    @php
				                    $tags = array($product->group, $product->category);
                                    @endphp
                                    <x-card-product :title="$product->display_name" class="product-block" :href="$product->slug" :tags="$tags"
                                                    :data-title="$product->display_name"
                                                    :data-group="$product->group"
                                                    :data-locations="$product->locations">{{ !empty($product->description)?$product->description:'View the product' }}</x-card-product>
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

    function init() {
        handleButtonClick();
        handleBackButtonClick();
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
        for (var i = 0; i < backButtons.length; i++) {

            backButtons[i].addEventListener('click', function (event) {
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

        for (var i = 0; i < els.length; i++) {
            els[i].classList.remove('active');
        }

        nav.querySelector('a').classList.add('active');

        form.reset();
    });

    var countries = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < countries.length; i++) {
        countries[i].addEventListener('click', selectCountry);
    }

    function selectCountry() {
        console.log('click');
    }
</script>
@endpush
