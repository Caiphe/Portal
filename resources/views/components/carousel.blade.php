@props(['wait', 'duration'])

@allowonce
<link rel="stylesheet" href="/css/components/carousel.css">
@endallowonce

<div 
    {{$attributes->merge(['class' => 'carousel'])}}
    data-wait="{{$wait ?? '5000'}}"
    data-duration="{{$duration ?? '0.340'}}s"
>
    {{$slot}}
</div>

@pushscript('carousel')
<script src="/js/components/carousel.js" defer></script>
@endpushscript