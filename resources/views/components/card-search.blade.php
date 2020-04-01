{{-- 
    This component allows for adding search cards.
    Eg:
    <x-card-search  title="My Apps" link="http://www.google.com">This is the description</x-card-search>
	title - the card title
	The card description is passed through the $slot and search attributes can be added to the card to be applied to the a tag
--}}

@allowonce('card_search')
<link href="/css/components/card-search.css" rel="stylesheet"/>
<link href="/css/components/card.css" rel="stylesheet"/>
@endallowonce

@props(['title', 'icon', 'link'])

@php 
    $cardText = strlen($slot) > 165 ? substr($slot,0,165) : $slot;
@endphp

<a href="{{ $link }}">
	<div class="card card--search">
		<div class="card__content">
			@isset($title)
			<h3 class="card__header">
				{{ $title }}
			</h3>
			@endisset
			<p class="card__body">
				{{ $cardText }}
            </p>
            <a href="{{ $link }}">{{ $link }}</a>
            @svg('arrow-forward', '#000000')
		</div>
	</div>
</a>	