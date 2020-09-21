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
            <p>Date created</p>
        </div>

        <div class="column">
            &nbsp;
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
            <x-app
                :app="$app"
                :attr="$app->attributes"
                :details="$app->developer"
                :countries="$productCountries ?: ['all' => 'Global']"
                type="approved">
            </x-app>
            @endif
        @empty
            <p>No apps.</p>
        @endforelse
        {{ $apps->links() }}
    </div>
</div>