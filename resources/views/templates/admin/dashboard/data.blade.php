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
        <div class="note">{!! $app['notes'] !!}</div>
    </x-dialog>
@endforeach
