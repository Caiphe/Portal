@php
    $countryList = $countries->pluck('name', 'code')->toArray();
@endphp

<div class="head">
    <p class="column-app-name">App name</p>
    <p class="column-country">Country</p>
    <p class="column-developer-company">Developer/company</p>
    <p class="column-go-live">Go live</p>
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
    <x-dialog id="admin-{{ $app->aid }}" class="note-dialog">
        <h3><em>{{ $app->display_name }}</em> log notes</h3>
        <div class="note">{!! $app['notes'] ?: 'No notes at the moment' !!}</div>
    </x-dialog>

    <x-dialog id="custom-attributes-{{ $app->aid }}" class="custom-attributes-dialog">
        <div class="content-container">
            <h3 class="dialog-heading">Custom attributes</h3>

            <div class="attributes-heading @if ($app->custom_attributes) show @endif">
                <h4 class="name-heading">Attribue Name</h4>
                <h4 class="value-heading">Value</h4>
            </div>

            {{-- Custom Attributes list --}}
            <form class="custom-attributes-list" method="post" action="{{ route('app.update.attributes', $app) }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="remove-check" class="remove-check" value=""/>
                @foreach ($app->custom_attributes as $key => $value)
                    <x-apps.custom-attribute :nameValue="$key" :valueValue="$value"></x-apps.custom-attribute>
                @endforeach


                <div class="no-attribute">None defined</div>
            </form>

            {{-- Custom attributes form --}}
            <form class="custom-attributes-form" action="">
                <div class="each-field">
                    <label for="name">Attribute name</label>
                    <input type="text" name="attribute[name][]" class="attribute-field attribute-name" placeholder="New Attribute name"/>
                </div>
                <div class="each-field">
                    <label for="value">Value</label>
                    <input type="text" name="attribute[value][]" class="attribute-field attribute-value" placeholder="New Value"/>
                </div>
                <button type="button" class="button add-attribute">Add</button>
                <div class="attribute-error">Attribute name and value required</div>
            </form>

        </div>
    </x-dialog>
@endforeach

