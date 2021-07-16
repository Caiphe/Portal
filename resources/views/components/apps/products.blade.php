@props(['app', 'products', 'for'])

@php
    $isDashboard = Request::is('admin/dashboard');
@endphp

@foreach($products as $product)
    <a
        href="{{route('product.show', $product['slug'] ?? '')}}"
        target="_blank"
        class="product product-status-{{ $product['pivot']['status'] }}"
        data-pid="{{ $product['pid'] }}"
        data-aid="{{ $app['aid'] }}"
        data-status="{{ $product['pivot']['status'] }}"
        data-product-display-name="{{ $product['display_name'] }}"
        data-for="{{ $for }}"
    >
        <span class="status-bar status-{{ $product['pivot']['status'] }}"></span>
        <span class="name">{{ $product['display_name'] }}</span>
        @if($isDashboard)
            <button class="product-approve" data-action="approve" data-for="{{ $for }}">
                @svg('thumbs-up', '#000000')
            </button>
            <button class="product-revoke" data-action="revoke" data-for="{{ $for }}">
                @svg('thumbs-down', '#000000')
            </button>
        @else
            @svg('arrow-forward', '#000000')
        @endif
    </a>
@endforeach
