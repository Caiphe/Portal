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

    {{--Start of Add custom attribute dialog--}}
    @include('templates.admin.dashboard.partial-app-custom-attributes.add-custom-attribute-modal', ['app' => $app])
    {{--End of Add attribute dialog--}}

    {{--Start of Edit custom attribute dialog --}}
    @include('templates.admin.dashboard.partial-app-custom-attributes.edit-custom-attribute-modal', ['app' => $app])
    {{--End of Edit custom attribute dialog--}}

    {{--Start of Add reserved attribute dialog--}}
    @include('templates.admin.dashboard.partial-app-custom-attributes.reserved-attributes.add-reserved-attribute-modal', ['app' => $app])
    {{--End of Add reserved attribute dialog--}}

    {{--Start of Edit reserved attribute dialog--}}
    @include('templates.admin.dashboard.partial-app-custom-attributes.reserved-attributes.edit-reserved-attribute-modal', ['app' => $app])
    {{--End of Edit reserved attribute dialog--}}

    {{--Start of Delete attribute dialog--}}
    @include('templates.admin.dashboard.partial-app-custom-attributes.delete-attributes-models.attributes-delete-modal', ['app' => $app])
    {{--End of Delete attribute dialog--}}

@endforeach


