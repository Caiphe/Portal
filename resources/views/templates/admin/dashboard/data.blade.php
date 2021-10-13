@php
    $countryList = $countries->pluck('name', 'code')->toArray();
@endphp

<div class="my-apps">
    <div class="head">
        <div class="column">
            <p>App name</p>
        </div>

        <div class="column">
            <p>Countries</p>
        </div>

        <div class="column">
            <p>Developer email</p>
        </div>

        <div class="column">
            <p>Requested go live on</p>
        </div>

    </div>
    <div class="body">
        @forelse($apps as $app)
            @if(!empty($app['attributes']))
                @php
                    $productCountries = [];
                    if(!is_null($app->country_code)){
                        $productCountries = $app->country()->pluck('name', 'code')->toArray();
                    } else {
                        $productCountries = $app->products->reduce(function($carry, $product) use ($countryList) {
                            $locationArray = explode(',', $product->locations);
                            $carry = array_merge($carry, array_intersect_key($countryList, array_combine($locationArray, $locationArray)));
                            return $carry;
                        }, []);
                    }
                @endphp
                <x-app-new
                    :app="$app"
                    :attr="$app->attributes"
                    :details="$app->developer"
                    :countries="$productCountries ?: ['all' => 'Global']"
                    type="approved">
                </x-app-new>
            @endif
        @empty
            @if(Request::is('admin/*'))
                <p>No apps to approve. You can still search for apps to view.</p>
            @else
                <p>No apps.</p>
            @endif
        @endforelse
        {{ $apps->withQueryString()->links() }}
    </div>
</div>

@foreach($apps as $app)
    <x-dialog id="admin-{{ $app->aid }}-note-dialog" class="note-dialog">
        <h3>Profile Log Notes</h3>
        <div class="note">{!! $app['notes'] !!}</div>
    </x-dialog>
@endforeach
