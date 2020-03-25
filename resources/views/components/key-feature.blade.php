@props(['title'])

@allowonce('key_feature')
<link href="/css/components/key-feature.css" rel="stylesheet"/>
@endallowonce

<div class="key-feature">
    <div class="body">
        <div class="icon">
            @svg('key', '#000000')
        </div>
        <h3 class="header">
            {{ $title }}
        </h3>
        <p class="content">
            {{ $slot }}
        </p>
    </div>
</div>
