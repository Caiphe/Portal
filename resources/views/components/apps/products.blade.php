@props(['products'])

@foreach($products as $product)
    <div class="product">
        <span class="status-{{ $product['status'] }}"></span>
        <span class="name">{{ $product['apiproduct'] }}</span>
        @if(Request::is('dashboard'))
            <button class="dashboard-approve">
                @svg('thumbs-up', '#000000')
            </button>
            <button class="dashboard-revoke">
                @svg('thumbs-down', '#000000')
            </button>
        @else
            @svg('arrow-forward', '#000000')
        @endif
    </div>
@endforeach
