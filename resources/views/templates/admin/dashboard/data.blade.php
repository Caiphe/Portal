@php
    $countryList = $countries->pluck('name', 'code')->toArray();
@endphp

<div class="head">
    <p class="column-app-name">App name</p>
    <p class="column-country">Country</p>
    <p class="column-developer-company">Developer/company</p>
    <p class="column-go-live">Created at</p>
    <p class="column-status">Status</p>
</div>

<div class="body">
    @forelse($apps as $app)
        @if(empty($app['attributes']))
            @continue
        @endif
        @php
            $productCountries = [];
            if(!is_null($app->country_code)){
                $productCountries = $app->country()->pluck('name', 'code')->toArray();
            }
        @endphp
        <x-dashboard
            :app="$app"
            :attr="$app->attributes"
            :details="$app->team ?? $app->developer"
            :countries="$productCountries ?: ['all' => 'Global']"
            type="approved">
        </x-dashboard>
    @empty
        <p>No apps to approve. You can still search for apps to view.</p>
    @endforelse

    {{ $apps->withQueryString()->links() }}
</div>

@foreach($apps as $app)

    <x-dialog-box id="admin-{{ $app->aid }}" dialogTitle="{{ $app->display_name }} log notes" class="log-content">
        <div class="note">
            {!! $app['notes'] ?: 'No notes at the moment here' !!}
        </div>
    </x-dialog-box>

    <x-dialog-box id="custom-attributes-{{ $app->aid }}" dialogTitle="Create a new Attribute" class="custom-attributes-dialog">

        <div class="form-container">
        <form>
            <div class="content-container">

                <div class="form-group">
                        <label for="type">Type</label>
                        <select id="type" name="type" onchange="handleAttributeTypeChange()">
                            <option value="string">String</option>
                            <option value="number">CSV String Array</option>
                            <option value="boolean">Boolean</option>
                        </select>
                        <p id="type-description">A string attribute is the default type of attribute and only accepts a text value without special characters or spaces.</p>
                    </div>

                    <!-- Name Field -->
                    <div class="form-group" id="name-field">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" placeholder="The name of the attribute" required>
                        <p class="error-message" id="name-error" style="color: red; display: none;"></p>
                    </div>

                    <!-- Value Field -->
                    <div class="form-group" id="value-field">
                        <label for="value">Value</label>
                        <input type="text" id="value" name="value" placeholder="The value of the attribute" required>
                        <p class="error-message" id="value-error" style="color: red; display: none;"></p>
                    </div>

                    <!-- Number Textarea Field (hidden initially) -->
                    <div class="form-group" id="number-field" style="display: none;">
                        <label for="number-value">Value</label>
                        <textarea id="number-value" name="number-value" placeholder="Enter number value"></textarea>
                        <div id="tag-container" class="tag-container"></div>
                    </div>

                    <!-- Boolean Select Field (hidden initially) -->
                    <div class="form-group" id="boolean-field" style="display: none;">
                        <label for="boolean-value">Value</label>
                        <select id="boolean-value" name="boolean-value">
                            <option value="true">True</option>
                            <option value="false">False</option>
                        </select>
                    </div>
            </div>


            {{--<div class="form-actions">
                <button type="submit" class="btn-attribute btn-confirm">Confirm</button>
                <button type="button" class="btn-attribute " id="btn-cancel" onclick="cancelForm()">Cancel</button>
            </div>--}}
        </form>
        </div>

        <div class="bottom-shadow-container button-container">
            <button class="status-dialog-button">Submit</button>
        </div>
    </x-dialog-box>
@endforeach


