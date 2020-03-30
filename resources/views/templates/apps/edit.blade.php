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

    <x-heading heading="Apps" tags="EDIT"></x-heading>

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
{{--                        @foreach($countries as $key => $country)--}}
{{--                            <label class="country" for="country-{{ $loop->index + 1 }}" data-location="{{ $key }}">--}}
{{--                                @svg('$key', '#000000', 'images/locations')--}}
{{--                                <input type="checkbox" id="country-{{ $loop->index + 1 }}" name="country-checkbox" data-location="{{ $key }}">--}}
{{--                                {{ $country }}--}}
{{--                            </label>--}}
{{--                        @endforeach--}}
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
{{--                        @foreach($countries as $key => $country)--}}
{{--                            <img src="/images/locations/{{$key}}.svg" title="{{$country}} flag" alt="{{$country}} flag" data-location="{{ $key }}">--}}
{{--                        @endforeach--}}
                    </div>

                    <div class="products">
{{--                        @foreach ($products as $category=>$products)--}}
{{--                            <div class="category" data-category="{{ $category }}">--}}
{{--                                <h3>{{ $category }}</h3>--}}
{{--                                @foreach ($products as $product)--}}
{{--                                    @php--}}
{{--                                        $tags = array($product->group, $product->category);--}}
{{--                                    @endphp--}}
{{--                                    <x-card-product :title="$product->display_name"--}}
{{--                                                    class="product-block"--}}
{{--                                                    :href="$product->slug"--}}
{{--                                                    :tags="$tags"--}}
{{--                                                    :addButtonId="$product->id"--}}
{{--                                                    :data-title="$product->display_name"--}}
{{--                                                    :data-group="$product->group"--}}
{{--                                                    :data-locations="$product->locations">{{ !empty($product->description)?$product->description:'View the product' }}--}}
{{--                                    </x-card-product>--}}
{{--                                @endforeach--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
                    </div>

                    <div class="form-actions">
                        <button class="dark outline back">Back</button>
                        <button class="dark" id="create">
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

    </script>
@endpush
