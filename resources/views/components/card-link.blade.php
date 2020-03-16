@allowonce('card_link')
<link href="/css/components/card-link.css" rel="stylesheet"/>
<link href="/css/components/card.css" rel="stylesheet"/>
@endallowonce

@props(['title', 'icon'])

<a {{ $attributes }}>
	<div class="card card--link">
		@svg($icon, '#000000')
		<div class="card__content">
			<h3 class="card__header">
				{{ $title }}
			</h3>
			<p class="card__body">
				{{ $slot }}
			</p>
			<button class="fab chevron-right"></button>
		</div>
	</div>
</a>	