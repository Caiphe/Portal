@props(['products'])

@foreach($products as $product)
    <a href="/products/@strSlug($product['apiproduct'])" class="product">
        <span class="status-{{ $product['status'] }}"></span>
        <span class="name">{{ $product['apiproduct'] }}</span>
        @if(Request::is('dashboard'))
            <form class="app-product-approve" action="{{ route('app.product.approve', $product['apiproduct']) }}" method="POST">
                @csrf
                <button class="product-approve" type="submit">
                    @svg('thumbs-up', '#000000')
                </button>
            </form>
            <button class="product-revoke">
                @svg('thumbs-down', '#000000')
            </button>
        @else
            @svg('arrow-forward', '#000000')
        @endif
    </a>
@endforeach
