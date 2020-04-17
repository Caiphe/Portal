@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/components/_accordion.css') }}">
@endpush

<div id="{{$id}}" class="accordion">
	<h3 class="">
        @svg('chevron-right')
		{{ $question }}
        <a href="#">
            @svg('link', '#000000')
        </a>
    </h3>
	<div class="answer">{{ $slot }}</div>
</div>
