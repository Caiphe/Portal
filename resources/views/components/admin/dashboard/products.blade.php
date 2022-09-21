@props(['app', 'products', 'for'])

@foreach($products as $product)
    <div
        class="product product-status-{{ $product['pivot']['status'] }}"
        data-pid="{{ $product['pid'] }}"
        data-aid="{{ $app['aid'] }}"
        data-product-slug="{{ $product['slug'] }}"
        data-status="{{ $product['pivot']['status'] }}"
        data-product-display-name="{{ $product['display_name'] }}"
        data-for="{{ $for }}"
    >
        <span class="status"></span>
        <a href="{{route('product.show', $product['slug'] ?? '')}}" target="_blank" class="name">{{ $product['display_name'] }}</a>
        <button class="reset product-status-action product-approve" data-action="approve" data-for="{{ $for }}" data-product-display-name="{{ $product['display_name'] }}" aria-label="Approve">
            @svg('approve')
        </button>
        <button class="reset product-status-action product-revoke" data-action="revoke" data-for="{{ $for }}" data-product-display-name="{{ $product['display_name'] }}" aria-label="Revoke">
            @svg('revoke')
        </button>
        <button class="reset log-notes" data-id="{{ $app->aid . $product->slug }}">View log notes</button>
        <button class="reset product-action">@svg('more-vert')@svg('chevron-right')</button>
    </div>

    <x-dialog-box id="admin-{{ $app->aid . $product->slug }}" class="note-dialog">
        <h3><em>{{ $product['display_name'] }}</em> log notes</h3>
        <div class="note">{!! $product['notes'] !!}</div>
    </x-dialog-box>
@endforeach
