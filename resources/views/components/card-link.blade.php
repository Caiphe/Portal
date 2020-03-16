@allowonce('card_link')
<link href="/css/components/card-link.css" rel="stylesheet"/>
<link href="/css/components/card.css" rel="stylesheet"/>
@endallowonce

@props(['title', 'icon'])

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
				{{ $slot }}
			</p>
			<button class="fab chevron-right"></button>
		</div>
	</div>
</a>	