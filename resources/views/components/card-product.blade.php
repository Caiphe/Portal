@allowonce('card_product')
<link href="/css/components/card-product.css" rel="stylesheet"/>
<link href="/css/components/card.css" rel="stylesheet"/>
@endallowonce

@props(['title'])

<a {{ $attributes }}>
	<div class="card card--product">
		<div class="card__content">
			@isset($title)
			<h3 class="card__header">
				{{ $title }}
			</h3>
			@endisset
			<p class="card__body">
				{{ $slot }}
			</p>
			<div class="buttons">
				<button class="inline">View</button>
				<button class=" inline fab plus dark"></button>
			</div>	
		</div>
	</div>
</a>	