@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/components/_accordion.css') }}">
@endpush

@props(['id', 'category'])

<div id="{{ $id }}" class="accordion">
	<h3 class="">
        @svg('chevron-right')
		{{ $category }}
        <a href="#">
            @svg('link', '#000000')
        </a>
    </h3>
	<div class="answer">{{ $slot }}</div>
</div>
