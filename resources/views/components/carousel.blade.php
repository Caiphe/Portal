@allowonce
<link rel="stylesheet" href="/css/components/carousel.css">
@endallowonce

<div {{$attributes->merge(['class' => 'carousel'])}}>
    {{$slot}}
</div>

@pushscript('carousel')
<script src="/js/components/carousel.js" defer></script>
@endpushscript