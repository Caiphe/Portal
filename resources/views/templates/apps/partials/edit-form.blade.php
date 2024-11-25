<input type="hidden" id="selectedCountry" name="selectedCountry" value="{{ $data['country_code'] }}">
<div class="group max-width-512">
    <h2>Basic Details</h2>
    <p class="text-mtn-grey">Enter your application's details</p>
</div>
<br>
<div class="group max-width-512">
    <label for="name">Application name *</label>
    <input type="text"
           name="name"
           id="name"
           value="{{ $data['display_name'] }}"
           data-checkurl='{{ route('app.name.check') }}'
           placeholder="Enter a name for the application"
           maxlength="100"
           autocomplete="off"
           data-validation-state="invalid"
    >
    <div id="nameCheck"
         class="nameCheck"
         data-token="{{ csrf_token() }}"
         data-check-uri="{{ route('app.name.duplicate.check') }}"
    >
        <img src="/images/icons/loading.svg" alt="Notice Icon">
        <p>Checking application name...</p>
    </div>
    <div id="name_error" class="error"></div>
</div>
<br>

<div class="group max-width-512">
    <label for="entity_name">Entity name *</label>
    <input type="text"
           name="entity_name"
            value="{{ $data['entity_name'] }}"
           id="entity_name"
           placeholder="Provide the partner integrating this app"
           maxlength="100"
           autocomplete="off"
    >
    <div id="entity_name_error" class="error"></div>
</div>
<br>

<div class="group max-width-512">
    <label for="contact_number">Contact Number *</label>
    <input type="text"
           name="contact_number"
           id="contact_number"
            value="{{ $data['contact_number'] }}"
           placeholder="Provide contact number for someone relevant to this app"
           maxlength="100"
           autocomplete="off"
           required
    >
    <div id="contact_number_error" class="error"></div>
</div>
<br>
<div class="group channels-group max-width-512">
    <label for="channels">Channels *</label>
    <p class="text-mtn-grey">Select which channels the app will use. This is only for statistical
        purposes and will not affect the availability of this app.</p>
    <div class="list">
        @php
            // Move this to the model and pass the values through the view response variables.
            $channels = [
                "MyMTN APP",
                "Facebook",
                "SMS",
                "Email",
                "Mobile App (Other)",
                "WhatsApp",
                "Voice Services",
                "Other External"
            ];

            $selectedChannels = explode(', ', $data['channels']);
        @endphp
        @foreach($channels as $channel)
            <div>
                <input
                    type="checkbox"
                    name="channels[]"
                    value="{{$channel}}"
                    @if(in_array($channel, $selectedChannels)) checked @endif
                >
                &nbsp;
                <span>{{$channel}}</span>
            </div>
        @endforeach
    </div>
    <div id="channel_error" class="error"></div>
</div>
<br>
<div class="group group-info max-width-512">
    <label for="url">Callback url @svg('info-icon', '#a5a5a5')<small class="tooltip">The callback URL
            typically specifies the URL of an app that is designated to receive an authorization code on
            behalf of the client app. In addition, this URL string is used for validation. A callback
            URL is required only for 3-legged Oauth</small></label>
    <input type="url" name="url" id="url" placeholder="Provide a callback URL" autocomplete="off" value="{{ $data['callback_url'] }}">
    <div class="error"></div>
</div>

<br>
<div class="group max-width-512">
    <label for="description">Description</label>
    <textarea name="description" id="description" rows="5" placeholder="Enter description">{{ $data['description'] }}</textarea>
    <div class="error"></div>
</div>
<br>

{{-- Product selection starts here --}}
<div id="product-selection">
    <div class="group max-width-512">
        <h2>Product Selection</h2>
        <p class="text-mtn-grey">
            Select the products you would like to add to your app. You have to select at least one.
        </p>
    </div>
    <div id="product_error" class="error"></div>
    <br>
    <div id="select-ui">
        <div id="product-selection-categories" class="grid-1">
            <div class="product-filter-block card-grey-border block-padding">
                <div class="filter-head">
                    <h3>Categories</h3>
                    <button type="button" class="clear-category custom-clear">Clear</button>
                </div>
                @foreach ($productCategories as $slug => $title)
                    <div class="filter-checkbox">
                        <input type="checkbox"
                               name="{{ $slug }}"
                               id="category-{{ $slug }}"
                               class="filter-products filter-category"
                               value="{{ $slug }}"
                               @if(isset($selectedCategory) && $selectedCategory === $title)
                                   checked=checked
                               @endif
                               autocomplete="off"
                        >
                        <label class="filter-label" for="category-{{ $slug }}">{{ $title }}</label>
                    </div>
                @endforeach
                <div class="group-filter">
                    <div class="filter-head">
                        <h3>Group</h3>
                        <button type="button" class="clear-group custom-clear">Clear</button>
                    </div>
                    <div class="custom-select-block">
                        <x-multiselect id="filter-group"
                                       name="filter-group"
                                       label="Select group"
                                       :options="$productGroups"/>
                        <img class="select-icon" src="/images/select-arrow.svg"/>
                    </div>
                </div>
            </div>
        </div>
        <div id="product-selection-products grid-2">
            <div id="product-select-info">
                <div id="product-selection-country-info">
                    <p>Showing products for</p>

                    @foreach($countries as $key => $country)
                        @if($key === $data['country_code'] )
                        <div id="flag-country" class="flag-country container-border block-location" data-location="{{ $key }}">
                            <img src="/images/locations/{{ $data['country_code'] }}.svg" alt="Country flag">
                            <p>{{ $country }}</p>
                        </div>
                        @endif
                    @endforeach
                    
                </div>
                <div id="product-selection-filter">
                    <input type="text"
                           name="filter-text"
                           id="filter-text"
                           class="filter-text"
                           placeholder="Search for products"
                           autofocus=""
                           autocomplete="off"
                    >
                </div>
            </div>
            <div id="no-products" class="card-grey-border">
                No products to display
            </div>
            <div id="product-list-selection" class="product-list-selection">
                <div class="products">
                    @foreach ($products as $category => $prods)
                        <div class="category" data-category="{{ $category }}">
                            <h3 class="category-heading category-title"
                                data-category="{{ $prods[0]->category_cid }}">
                                {{ $category }}
                            </h3>

                            @foreach ($prods as $prod)
                                <x-product-select
                                    :displayName="$prod->display_name"
                                    :name="$prod->name"
                                    :href="route('product.show', $prod->slug)"
                                    :slug="$prod->slug"
                                    :data-title="$prod->name"
                                    :data-group="$prod->group"
                                    :data-access="$prod->access"
                                    :data-category="$prod->category_cid"
                                    :data-locations="$prod->locations"
                                    :selectedProducts="$selectedProducts">
                                    >
                                </x-product-select>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Poroducts selection ends here --}}
