@props(['products'])

@foreach($products as $product)
    <a 
        href="{{route('product.show', $product['slug'])}}"
        class="product"
        data-pid="{{ $product['pid'] }}"
        data-aid="{{ $product['pivot']['app_aid'] }}"
        data-status="{{ $product['pivot']['status'] }}"
        data-product-display-name="{{ $product['display_name'] }}"
    >
        <span class="status-bar status-{{ $product['pivot']['status'] }}"></span>
        <span class="name">{{ $product['display_name'] }}</span>
        @if(Request::is('dashboard'))
            <button class="product-approve" data-action="approve" data-aid="{{ $product['pivot']['app_aid'] }}" data-pid="{{ $product['pivot']['product_pid'] }}" data-product-display-name="{{ $product['display_name'] }}">
                @svg('thumbs-up', '#000000')
            </button>
            <button class="product-revoke" data-action="revoke" data-aid="{{ $product['pivot']['app_aid'] }}" data-pid="{{ $product['pivot']['product_pid'] }}" data-product-display-name="{{ $product['display_name'] }}">
                @svg('thumbs-down', '#000000')
            </button>
        @else
            @svg('arrow-forward', '#000000')
        @endif
    </a>
@endforeach
