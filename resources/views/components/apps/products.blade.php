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
        <div class="buttons-block-container">
            <button class="product-status-action product-approve" data-action="approve" data-for="{{ $for }}">
                @svg('thumbs-up', '#000000') Approve
            </button>
            <button class="product-status-action product-revoke" data-action="revoke" data-for="{{ $for }}">
                @svg('thumbs-down', '#000000') Revoke
            </button>
            <button class="log-notes" data-id="{{ $app->aid . $product->slug }}">View Log Notes</button>
        </div>
        @else
            @svg('arrow-forward', '#000000')
        @endif
    </a>
    <x-dialog id="{{ $app->aid . $product->slug }}-note-dialog" class="note-dialog">
        <h3>Profile Log Notes</h3>
        <div class="note">{!! $product['notes'] !!}</div>
    </x-dialog>
@endforeach
