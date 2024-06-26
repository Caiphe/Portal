@extends('layouts.admin')

@section('title', 'Create app')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/apps/create.css') }}">
@endpush

@section('content')
    <h1>Create app</h1>
    {{-- <x-heading heading="Apps" tags="CREATE NEW"></x-heading> --}}

    <x-twofa-warning class="tall"></x-twofa-warning>

    <div class="content">

        <nav>
            <button type="button" @class(['reset', 'active' => !isset($chosenUser)])><span>1</span> App owner</button>
            <button type="button" @class(['reset', 'active' => isset($chosenUser)])><span>2</span> App details</button>
            <button type="button" class="reset "><span>3</span> Select a country</button>
            <button type="button" class="reset "><span>4</span> Select products</button>
            <button type="button" class="reset "><span>5</span> Complete</button>
        </nav>

        <form id="form-create-app">
            <div class="app-owner-container create-app-section @if(!isset($chosenUser)) active @endif">
                <span class="apps-top-text">Create a new app</span>
                <h2 class="app-create-heading">App owner</h2>
                <span class="gray-text">Modify or continue with the assigned app creator</span>
                <div class="owner-avatar-container">
                    <div class="owner-avatar"></div>
                </div>

                <span class="gray-text">Assigned app creator</span>
                <div class="creator-email"> No creator assigned</div>

                <div class="group owners-list-container">
                    <div class="wrapper">
                        <div class="search-input">
                            <input type="text" class="search-field" name="app-owner" value="{{ $chosenUser->email ?? '' }}" placeholder="Search for email address..." autocomplete="off">
                            <div class="autocom-box">
                                <!-- here list are inserted from javascript -->
                            </div>
                        </div>
                    </div>

                    <div class="apps-button-container">
                        <button type="button" class="linkBtn" id="assign-to-me">Assign to me</button>
                        <button type="button" class="linkBtn remove-thumbnail" id="remove-assignee">Remove assignee</button>
                    </div>
                </div>

                <div class="actions-btn-container">
                    <a class="button white" href="{{ url()->previous() }}">Cancel</a>
                    <button id="next-app-owner" class="btn primary next" type="button">Next</button>
                </div>
            </div>

            <div class="app-details-step create-app-section @if(isset($chosenUser)) active @endif" >
                {{-- @svg('app-avatar', '#ffffff') --}}
                {{-- Section Heading --}}
                <div class="apps-heading-container">
                    <span class="apps-top-text">Create a new app</span>
                    <h2 class="app-create-heading">App details</h2>
                    <span class="gray-text">Edit basic details of the app</span>
                </div>
                {{-- Section Heading ends --}}

                <div class="group">
                    <label for="name">Application Name *</label>
                    <input type="text" name="name" id="name" placeholder="Enter name" maxlength="100" autocomplete="off" required>
                    <div class="error"></div>
                </div>

                <div class="group">
                    <label for="url">Callback url</label>
                    <input type="url" name="url" id="url" autocomplete="off" placeholder="Enter callback url (eg. https://callback.com)">
                    <div class="error"></div>
                </div>

                <div class="group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="5" placeholder="Enter description"></textarea>
                </div>

                {{-- Custom attributes --}}
                <div class="custom-attribute-list-container">
                    <h5 class="custom-attribute-heading">Custom attributes</h5>

                    <span class="no-attribute">None defined</span>

                    <div class="attributes-heading">
                        <h4 class="name-heading">Attribute name</h4>
                        <h4 class="value-heading">Value</h4>
                    </div>

                    <div class="custom-attributes-list" id="custom-attributes-list"></div>

                </div>

                <div class="custom-attributes-form" action="">
                    <div class="each-field">
                        <label for="name">Attribute name</label>
                        <input type="text" value="" name="attribute_name" id="attribute-name" class="attribute-field attribute-name" placeholder="New attribute name"/>
                    </div>
                    <div class="each-field">
                        <label for="value">Value</label>
                        <input type="text" value="" name="attribute_value" id="attribute-value" class="attribute-field attribute-value" placeholder="New value"/>
                    </div>
                    <button type="button" class="button btn dark outline add-attribute" id="add-attribute">Add</button>
                </div>
                <div class="attribute-error" id="attribute-error">Attribute name and value required</div>

                {{-- Custom attributes ends --}}

                <div class="actions-btn-container">
                    <button type="button" class="button white back">Back</button>
                    <button type="button" id="next-app-details" class="btn primary next apps-create-btn">Select country</button>
                </div>
            </div>

            <div class="select-countries create-app-section">
                <div class="apps-heading-container">
                    <span class="apps-top-text">Create a new app</span>
                    <h2 class="app-create-heading">Select a country</h2>
                    <span class="gray-text">Select a country you would like to associate with your app *</span>
                </div>

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

                <div class="actions-btn-container">
                    <button type="button" class="button btn white back">Back</button>
                    <button type="button" id="next-select-products" class="btn primary next" id="select-products-button"> Select products</button>
                </div>
            </div>

            <div class="select-products create-app-section">
                <div class="apps-heading-container">
                    <span class="apps-top-text">Create a new app</span>
                    <h2 class="app-create-heading">Select products</h2>
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
                        <div class="category" data-category="{{ $products[0]->category_cid }}">
                            <h3>{{ $category }}</h3>
                            @foreach ($products as $product)
                                @php
                                    $tags = array($product->category->title, $product->group);
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
                                                :data-category="$product->category_cid"
                                                :data-locations="$product->locations">{{ !empty($product->description)?$product->description:'View the product' }}
                                </x-card-product>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <div class="actions-btn-container">
                    <button type="button" class="btn button white back">Back</button>
                    <button type="button" id="next-create-app" class="btn primary next">Create app</button>
                </div>
            </div>

            <div class="complete-container create-app-section">
                <div class="apps-heading-container">
                    <span class="apps-top-text">Create a new app</span>
                    <h2 class="app-create-heading">Complete</h2>
                    @svg('check-complete', '#FFCC00')
                </div>

                <div class="success-message-container">
                    <h2 class="success-message">Application created successfully</h2>

                    <a class="back-btn" href="{{ route('admin.dashboard.index') }}">Back to dashboard</a>
                </div>
            </div>

        </form>
    </div>
    <template id="custom-attribute" hidden>
        <x-apps.custom-attribute></x-apps.custom-attribute>
    </template>
@endsection

@push('scripts')
<script>
    function adminAppsCreateLookup(key) {
        return {
            'userEmails': @json($userEmails),
            'userProfiles': @json($userProfiles),
            'appCreatorEmail': '{{ $appCreatorEmail }}',
            'appStoreUrl': '{{ route('app.store') }}',
            'csrfToken': '{{ csrf_token() }}'
        }[key] ?? null;
    }
</script>
<script src="{{ mix('/js/templates/admin/apps/create.js') }}" defer></script>
@endpush
