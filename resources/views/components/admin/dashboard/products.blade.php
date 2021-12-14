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
        <span class="status status-{{ $product['pivot']['status'] }}">{{ $product['pivot']['status'] }}</span>
        <a href="{{route('product.show', $product['slug'] ?? '')}}" target="_blank" class="name">{{ $product['display_name'] }}</a>
        <button class="reset product-status-action product-approve" data-action="approve" data-for="{{ $for }}">
            @svg('approve') Approve
        </button>
        <button class="reset product-status-action product-revoke" data-action="revoke" data-for="{{ $for }}">
            @svg('revoke') Revoke
        </button>
        <button class="reset log-notes" data-id="{{ $app->aid . $product->slug }}">View log notes</button>
    </div>
    <x-dialog id="admin-{{ $app->aid . $product->slug }}-note-dialog" class="note-dialog">
        <h3>Profile log notes</h3>
        <div class="note">{!! $product['notes'] !!}</div>
    </x-dialog>
@endforeach
