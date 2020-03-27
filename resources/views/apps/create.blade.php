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

            <form action="">

                <div>
                    @svg('app-avatar', '#ffffff')
                    <div class="group">
                        <label for="name">Name your app *</label>
                        <input type="text" name="name" id="name" placeholder="Enter name">
                    </div>

                    <div class="group">
                        <label for="name">Callback url *</label>
                        <input type="text" name="name" id="name" placeholder="Enter callback url">
                    </div>

                    <div class="group">
                        <label for="description">Description *</label>
                        <textarea name="description" id="description" rows="5" placeholder="Enter description"></textarea>
                    </div>

                    <button class="dark" type="submit">
                        Select regions
                        @svg('arrow-forward', '#ffffff')
                    </button>
                </div>

                <div>
                    <p>Select the regions you would like to associate with your app *</p>

                    <div class="countries">
                        <div class="country">
                            @svg('za', '#ffffff', 'images/locations')
                            <span>
                            Country
                        </span>
                        </div>

                        <div class="country">
                            @svg('za', '#ffffff', 'images/locations')
                            <span>
                            Country
                        </span>
                        </div>

                        <div class="country">
                            @svg('za', '#ffffff', 'images/locations')
                            <span>
                            Country
                        </span>
                        </div>

                        <div class="country">
                            @svg('za', '#ffffff', 'images/locations')
                            <span>
                            Country
                        </span>
                        </div>


                        <div class="country">
                            @svg('za', '#ffffff', 'images/locations')
                            <span>
                            Country
                        </span>
                        </div>


                        <div class="country">
                            @svg('za', '#ffffff', 'images/locations')
                            <span>
                            Country
                        </span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button class="dark outline">Back</button>
                        <button class="dark">
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
                        <button class="dark outline">Back</button>
                        <button class="dark">
                            Create app
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <button type="reset">Cancel</button>
    </div>

@endsection

@push('scripts')
<script>
    var form = document.querySelector('form');



</script>
@endpush
