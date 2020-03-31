@props(['products'])

@foreach($products as $product)
    <div class="product">
        <span class="status-{{ $product['status'] }}"></span>
        <span class="name">{{ $product['apiproduct'] }}</span>
        @if(Request::is('dashboard'))
            <button class="product-approve" data-product="{{ $product['apiproduct'] }}" data-method="approve">
                @svg('thumbs-up', '#000000')
            </button>
            <button class="product-revoke" data-product="{{ $product['apiproduct'] }}" data-method="revoke">
                @svg('thumbs-down', '#000000')
            </button>
        @else
            @svg('arrow-forward', '#000000')
        @endif
    </div>
@endforeach
