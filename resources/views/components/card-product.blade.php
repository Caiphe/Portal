{{--
    This component allows for adding product cards.
    Eg:
    <x-card-product :countries="$countries" :tags="$tags" target="_blank" title="API Name" href="http://www.google.com" addButtonId="button-id" addUrl="http://www.google.com">This is the description</x-card-product>
    countries - is an array of country names, which is converted to slug friendly text for the country svg files e.g. ['za','cm','ci']
    tags -  is an array the product tags e.g. ['MTN API','MTN APP']
    title - the card title
    href - the link for the card to go to
    addButtonId - id for add button, if empty will hide the add button
    addUrl - The url for the add button
    The card description is passed through the $slot and link attributes can be added to the card to be applied to the a tag
--}}

@once
@push('styles')
<link href="{{ mix('/css/components/card-product.css') }}" rel="stylesheet"/>
<link href="{{ mix('/css/components/card.css') }}" rel="stylesheet"/>
@endpush
@endonce

@props(['title','countries','tags', 'href', 'addButtonId', 'addUrl', 'target' => '_self'])
<div {{ $attributes->merge(['class' => 'card card--product']) }} >
    <a href="{{$href}}" target="{{ $target }}">
        <div class="card__content">
            @isset($tags)
                @foreach ($tags as $tag)
                    <span class="tag outline yellow">{{$tag}}</span>
                @endforeach
            @endisset
            @isset($title)
            <h3 class="card__header">
                {{ str_replace("_", " ", $title) }}
            </h3>
            @endisset
            <p class="card__body">
                {{ $slot }}
            </p>
            @isset($countries)
            <div class="country-selector">
                <div class="countries">
                    @foreach ($countries as $name => $country)
                        <img src="/images/locations/{{$country}}.svg" title="{{ gettype($name) === 'string' ? $name : $country }} flag" alt="{{$country}} flag">
                    @endforeach
                </div>
                @if (count($countries ) > 1)
                    <div class="view-more">+ {{count($countries )-1}} more</div>
                @endif
            </div>
            @endisset
        </div>
    </a>
    <div class="buttons">
        <a class="flex button" href="{{$href}}" role="button">View</a>
        @isset($addButtonId)
		<a
		@isset($addUrl)
		href="{{$addUrl}}"
		@endisset
		id="{{ $addButtonId }}" data-name="{{$dataTitle}}" class="flex button fab plus dark"></a>
        @endisset
    </div>
</div>
