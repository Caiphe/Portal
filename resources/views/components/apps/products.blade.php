@props(['app', 'products'])

@php
    $credentialProducts = $app->products->filter(fn($product) => in_array($product->name, $products));
    $appStatus = !is_null($app->live_at) && count($app->credentials) === 1 ? 'app-status-pending' : '';
    $isDashboard = Request::is('admin/dashboard');
@endphp

@foreach($credentialProducts as $product)
    <a 
        href="{{route('product.show', preg_replace('/[-_]prod$/i', '', $product['slug']))}}"
        class="product product-status-{{ $product['pivot']['status'] }}"
        data-pid="{{ $product['pid'] }}"
        data-aid="{{ $app['name'] }}"
        data-status="{{ $product['pivot']['status'] }}"
        data-product-display-name="{{ $product['display_name'] }}"
    >
        <span class="status-bar status-{{ $product['pivot']['status'] }} {{ $appStatus }}"></span>
        <span class="name">{{ preg_replace('/prod$/i', '', $product['display_name']) }}</span>
        @if($isDashboard)
            <button class="product-approve" data-action="approve">
                @svg('thumbs-up', '#000000')
            </button>
            <button class="product-revoke" data-action="revoke">
                @svg('thumbs-down', '#000000')
            </button>
        @else
            @svg('arrow-forward', '#000000')
        @endif
    </a>
@endforeach
