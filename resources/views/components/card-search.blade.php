{{-- 
    This component allows for adding search cards.
    Eg:
    <x-card-search  title="My Apps" link="http://www.google.com">This is the description</x-card-search>
	title - the card title
	The card description is passed through the $slot and search attributes can be added to the card to be applied to the a tag
--}}

@once
@push('styles')
<link href="{{ mix('/css/components/card-search.css') }}" rel="stylesheet"/>
@endpush
@endonce

@props(['title', 'icon', 'link'])

<a {{$attributes->merge(['class' => 'search-card'])}} href="{{ $link }}">
	@isset($title)
	<h3 class="header">
		{{ $title }}
	</h3>
	@endisset
	<p class="body">
		{!! $slot !!}
    </p>
    <span class="href">{{ strlen($link) > 60 ? substr($link, 0, 60) . '...' : $link }}</span>
    @svg('arrow-forward', '#000000')
</a>	