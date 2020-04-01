@props(['products'])

@foreach($products as $product)
    <div class="product" data-name="{{ $product['apiproduct'] }}">
        <span class="status-{{ $product['status'] }}"></span>
        <span class="name">{{ $product['apiproduct'] }}</span>
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
    </div>
@endforeach
