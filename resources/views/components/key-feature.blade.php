@props(['title', 'icon' => 'key'])

@allowonce('key_feature')
<link href="{{ mix('/css/components/key-feature.css') }}" rel="stylesheet"/>
@endallowonce

<div {{ $attributes->merge(['class' => 'key-feature']) }}>
    <div class="body">
        <div class="icon">
            @svg($icon, '#000000')
        </div>
        <h3 class="header">
            {{ $title }}
        </h3>
        <p class="content">
            {{ $slot }}
        </p>
    </div>
</div>
