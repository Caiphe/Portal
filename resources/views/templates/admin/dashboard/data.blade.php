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
        @if(empty($app['attributes'])) @continue @endif
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

    <x-dialog-box id="custom-attributes-{{ $app->aid }}" dialogTitle="Custom attributes" class="custom-attributes-dialog">
        <div class="content-container">

            <div class="attributes-heading @if ($app->custom_attributes) show @endif">
                <h4 class="name-heading">Attribute name</h4>
                <h4 class="value-heading">Value</h4>
            </div>

            {{-- Custom Attributes list --}}
            <div id="custom-attributes-form-partial-{{ $app->aid }}">
                @include('partials.custom-attributes.form', ['app' => $app])
            </div>
        </div>

            {{-- Custom attributes form --}}
        <form class="custom-attributes-form" action="">
            <div class="each-field">
                <label for="name">Attribute name</label>
                <input type="text" name="attribute[name][]" class="attribute-field attribute-name" placeholder="New attribute name"/>
            </div>
            <div class="each-field">
                <label for="value">Value</label>
                <input type="text" name="attribute[value][]" class="attribute-field attribute-value" placeholder="New value"/>
            </div>
            <button type="button" class="button add-attribute">Add</button>
            <div class="attribute-error">Attribute name and value required</div>
        </form>

    </x-dialog-box>
@endforeach

