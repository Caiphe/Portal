{{--
    This component is for adding an accordion menu.
    Eg.
    <x-sidebar-accordion id="sidebar-products" active="/products/product-1" :list="[ 
        'Category 1' => [
            ['label' => 'Product 1', 'link' => '/products/product-1'],
            ['label' => 'Product 2', 'link' => '/products/product-2'],
            ['label' => 'Product 3', 'link' => '/products/product-3/']
        ],
        'Category 2' => [
            ['label' => 'Product 4', 'link' => '/products/product-4'],
            ['label' => 'Product 5', 'link' => '/products/product-5'],
            ['label' => 'Product 6', 'link' => '/products/product-6/']
        ],
    ]"/>

    id: The id of the sidebar accordion
    active: Either the label or link that should be active. I would suggest using the link since it is more likely to be unique
    list: A associative array of what builds up the nav
        label: What is displayed to the user
        link: What the link should be for the menu item
--}}
@props(['id', 'list', 'active'])
@allowonce('sidebar_accordion')
<link href="/css/components/sidebar-accordion.css" rel="stylesheet"/>
@endallowonce

@php
    $isSingle = count($list) === 1;
    $active = $active ?? '';
@endphp

<div id="sidebar-accordion" {{ $attributes->merge(['class' => 'accordion-items']) }}>
    <ul id="{{ $id }}" class="accordion-menu">    
        @foreach ($list as $accordion => $items)
            @php
            $isOpen = in_array($active, array_merge(array_column($items, 'label'), array_column($items, 'link')));
            @endphp
            <li class="accordion-item {{ $isSingle ? 'show no-svg' : '' }} @if($isOpen) show @endif">
                <a href="#" data-toggle="accordion-item"> {{ $accordion }} @svg('chevron-right', '#000000')</a>
                <ul class="accordion-sub-items">
                @foreach($items as $sub_item)
                    <li class="accordion-sub-item">
                        <a href="{{ $sub_item['link'] }}" @if($active === $sub_item['label'] || $active === $sub_item['link']) class="active" @endif>
                            {{$sub_item['label']}}@svg('arrow-forward', '#000000')
                        </a>
                    </li>
                @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</div>

@pushscript('sidebar_accordion')
<script src="/js/components/sidebar-accordion.js" defer></script>
@endpushscript