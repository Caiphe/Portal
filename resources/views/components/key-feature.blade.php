@props(['title', 'icon' => 'key'])

@once
@push('styles')
<link href="{{ mix('/css/components/key-feature.css') }}" rel="stylesheet"/>
@endpush
@endonce

<div {{ $attributes->merge(['class' => 'key-feature']) }}>
    <div class="body">
        <div class="icon">
            <div class="body">
                @svg($icon, '#000000')
            </div>
        </div>
        <h3 class="header">
            {{ $title }}
        </h3>
        <p class="content">
            {{ $slot }}
        </p>
    </div>
</div>
