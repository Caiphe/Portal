@allowonce('card_link')
<link href="/css/components/card-link.css" rel="stylesheet"/>
<link href="/css/components/card.css" rel="stylesheet"/>
@endallowonce

@props(['title', 'icon', 'linkUrl'])

<a href="{{ $linkUrl }}" {{ $attributes }}>
	<div class="mtn-card mtn-card--link">
		@svg($icon, '#000000')
		<div class="mtn-card__content">
			<h3 class="mtn-card__header">
				{{ $title }}
			</h3>
			<p class="mtn-card__body">
				{{ $slot }}
			</p>
			<button class="fab chevron-right"></button>
		</div>
	</div>
</a>	