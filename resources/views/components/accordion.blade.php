@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/components/_accordion.css') }}">
@endpush

@props(['id' => '', 'title' => '', 'link' => '', 'icon' => ''])

<div id="{{ $id }}" class="accordion">
    @svg('chevron-right')
	<h3 class="">
		{{ $title }}
        @isset($icon)
            <a href="#">
                @svg($icon, '#000000')
            </a>
        @endisset
    </h3>
{{--	<div class="answer">--}}
{{--        {{ $slot }}--}}
{{--    </div>--}}
</div>
