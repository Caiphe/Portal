{{-- 
    This component allows for adding product cards.
    Eg:
    <x-card-product :countries="$countries" :tags="$tags" target="_blank" title="API Name" href="http://www.google.com">This is the description</x-card-product>
    countries - is an array of country names, which is converted to slug friendly text for the country svg files e.g. ['South Africa','Cameroon','Ivory Coast']
	tags -  is an array the product tags e.g. ['MTN API','MTN APP']
	title - the card title
	The card description is passed through the $slot and link attributes can be added to the card to be applied to the a tag
--}}

@allowonce('card_product')
<link href="/css/components/card-product.css" rel="stylesheet"/>
<link href="/css/components/card.css" rel="stylesheet"/>
@endallowonce

@props(['title','countries','tags'])

<div class="card card--product">
	<a {{ $attributes }}>
		<div class="card__content">
			@isset($tags)
				@foreach ($tags as $tag)
					<span class="tag outline yellow">{{$tag}}</span>
				@endforeach
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
						<img src="/images/countries/{{Str::slug($country,"-")}}.svg" alt="{{$country}} flag">
					@endforeach
				</div>
				<div class="view-more">+ {{count($countries )-1}} more</div>
			</div>	
			@endisset	
		</div>
	</a>
	<div class="buttons">
		<button class="inline fab plus dark"></button>
	</div>
</div>