@props(['products'])

@foreach($products as $product)
    <div class="product">
        <span class="status-{{ $product['status'] }}"></span>
        <span class="name">{{ $product['apiproduct'] }}</span>
        @if(Request::is('dashboard'))
            <span>
                @svg('thumbs-up', '#000000')
            </span>
            <span>
                @svg('thumbs-down', '#000000')
            </span>
        @else
            @svg('arrow-forward', '#000000')
        @endif
    </div>
@endforeach
