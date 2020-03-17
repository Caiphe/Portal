@props(['icon', 'title'])

@allowonce('key_feature')
<link href="/css/components/key-feature.css" rel="stylesheet"/>
@endallowonce

<div class="key-feature">
    <div class="key-feature__body">
        <div class="key-feature__icon">
            @svg($icon, '#000000')
        </div>
        <h3 class="key-feature__header">
            {{ $title }}
        </h3>
        <p class="key-feature__content">
            {{ $slot }}
        </p>
    </div>
</div>
