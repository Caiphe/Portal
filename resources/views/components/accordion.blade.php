@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/components/_accordion.css') }}">
@endpush

@props(['id' => '', 'title' => '', 'link' => '', 'icon' => ''])

<div id="{{ $id }}" class="accordion" data-category="{{ $id }}">
    <div class="title">
        @svg('chevron-right')
        <h3>
            {{ $title }}
            @isset($icon)
                <a href="#{{ $link }}">
                    @svg($icon, '#000000')
                </a>
            @endisset
        </h3>
    </div>
    {{ $slot }}
</div>
