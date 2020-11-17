@props(['app', 'products'])

@php
    $credentialProducts = $app->products->filter(fn($product) => in_array($product->name, $products));
    $appStatus = !is_null($app->live_at) && count($app->credentials) === 1 ? 'app-status-pending' : '';
@endphp

@foreach($credentialProducts as $product)
    <a 
        href="{{route('product.show', preg_replace('/[-_]prod$/i', '', $product['slug']))}}"
        class="product"
        data-pid="{{ $product['pid'] }}"
        data-aid="{{ $app['name'] }}"
        data-status="{{ $product['pivot']['status'] }}"
        data-product-display-name="{{ $product['display_name'] }}"
    >
        <span class="status-bar status-{{ $product['pivot']['status'] }} {{ $appStatus }}"></span>
        <span class="name">{{ preg_replace('/prod$/i', '', $product['display_name']) }}</span>
        @svg('arrow-forward', '#000000')
    </a>
@endforeach
