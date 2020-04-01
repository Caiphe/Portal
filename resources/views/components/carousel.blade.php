{{--
    The carousel component tries to not have any hard opinions but is just a shell for styling,
    instead adds functionality to all the children inside it.
    This means that you would need to add your own classes with heights and widths wo the parent to get it to show.
    This component relies on there being at least one <x-carousel-item> component.
    eg: 
    <x-carousel wait="5000" duration="0.34">
        <x-carousel-item>One</x-carousel-item>
        <x-carousel-item>Two</x-carousel-item>
    </x-carousel>

    Wait: The time spent on a carousel item.
    Duration: The time it takes to move to another carousel item.
    Auto Scroll: Whether the carousel should scroll by itself.
--}}
@props(['wait', 'duration', 'autoScroll'])

@allowonce
<link rel="stylesheet" href="{{ mix('/css/components/carousel.css') }}">
@endallowonce

<div 
    {{$attributes->merge(['class' => 'carousel'])}}
    data-wait="{{$wait ?? '5000'}}"
    data-duration="{{$duration ?? '0.34'}}"
    data-auto-scroll="{{$autoScroll ?? 'true'}}"
>
    {{$slot}}
</div>

@pushscript('carousel')
<script src="/js/components/carousel.js" defer></script>
@endpushscript