@props(['products'])

@foreach($products as $product)
    <a href="{{route('product.show', $product['slug'])}}" class="product" data-name="{{ $product['name'] }}" data-status="{{ $product['pivot']['status'] }}">
        <span class="status-{{ $product['pivot']['status'] }}"></span>
        <span class="name">{{ $product['display_name'] }}</span>
        @if(Request::is('dashboard'))
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
