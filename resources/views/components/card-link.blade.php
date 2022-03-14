{{-- 
    This component allows for adding link cards.
    Eg:
    <x-card-link target="_blank" icon="twitter" title="My Apps" href="http://www.google.com">This is the description</x-card-link>
    icon -  is the icon name for the icon on the card e.g. twitter
	title - the card title
	The card description is passed through the $slot and link attributes can be added to the card to be applied to the a tag
--}}

@once
@push('styles')
<link href="{{ mix('/css/components/card-link.css') }}" rel="stylesheet"/>
<link href="{{ mix('/css/components/card.css') }}" rel="stylesheet"/>
@endpush
@endonce

@props(['title', 'icon'])

@php 
    $card_text = strlen($slot) > 165 ? substr($slot,0,200) : $slot;
@endphp

<a {{ $attributes }}>
	<div class="card card--link">
		@isset($icon)
			@svg($icon, '#000000')
		@endisset
		<div class="card__content">
			@isset($title)
			<h3 class="card__header">
				{{ $title }}
			</h3>
			@endisset
			<p class="card__body">
				{{ $card_text }}
			</p>
		</div>
		<button class="fab chevron-right"></button>
	</div>
</a>	