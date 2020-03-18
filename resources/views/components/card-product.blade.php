@allowonce('card_product')
<link href="/css/components/card-product.css" rel="stylesheet"/>
<link href="/css/components/card.css" rel="stylesheet"/>
<!-- <script src="/js/components/card-product.js"></script> -->
@endallowonce

@props(['title','countries','tags'])

<a {{ $attributes }}>
	<div class="card card--product">
		<div class="card__content">
			@isset($tags)
			<div class="tags">
			@foreach ($tags as $tag)
					<div class="tag outline yellow">{{$tag}}</div>
			@endforeach
			</div>	
			@endisset
			@isset($title)
			<h3 class="card__header">
				{{ $title }}
			</h3>
			@endisset
			<p class="card__body">
				{{ $slot }}
			</p>
			@isset($countries)
			<div class="country-selector">
				<div class="countries">
					@foreach ($countries as $country)
						<img src="/images/{{Str::slug($country,"-")}}.svg" alt="{{$country}} flag">
					@endforeach
				</div>
				<div class="view-more">+ {{count($countries )-1}} more</div>
			</div>	
			@endisset	
			<div class="buttons">
				<button class="inline">View</button>
				<button class="inline fab plus dark"></button>
			</div>	
		</div>
	</div>
</a>