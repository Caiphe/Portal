{{--
    This component allows for adding product cards.
    Eg:
    <x-card-product :countries="$countries" :tags="$tags" target="_blank" title="API Name" href="http://www.google.com" addButtonId="button-id" showAddButton="true" :selected="false" addUrl="http://www.google.com">This is the description</x-card-product>
    countries - is an array of country names, which is converted to slug friendly text for the country svg files e.g. ['za','cm','ci']
    tags -  is an array the product tags e.g. ['MTN API','MTN APP']
    title - the card title
    href - the link for the card to go to
    addButtonId - id for add button, if empty will hide the add button
    selected - whether the card is set as selected
    addUrl - The url for the add button
    The card description is passed through the $slot and link attributes can be added to the card to be applied to the a tag
--}}

@once
@push('styles')
<link href="{{ mix('/css/components/card-product.css') }}" rel="stylesheet"/>
<link href="{{ mix('/css/components/card.css') }}" rel="stylesheet"/>
@endpush
@endonce

@props(['title','countries', 'tags', 'href', 'showAddButton' => 'false', 'selected' => false, 'addButtonId', 'addUrl', 'target' => '_blank'])

@isset($addButtonId)
<input id="{{ $addButtonId }}" type="checkbox" class="add-product" name="add_product[]" value="{{ $dataTitle }}" @if($selected) checked @endif hidden autocomplete="off">
@endisset
<div {{ $attributes->merge(['class' => 'card card--product']) }} >
    <div class="card__inner_container">

    <a href="{{ $href }}" target="{{ $target }}">
        <div class="card__content">
            @isset($tags)
                @foreach ($tags as $tag)
                    <span class="tag">{{ $tag }}</span>
                @endforeach
                @if(isset($dataAccess) && $dataAccess !== 'public')
                <span class="tag {{ $dataAccess }}">{{ $dataAccess }}</span>
                @endif
            @endisset

            @isset($title)
            <h3 class="card__header">
                {{ Str::limit(str_replace("_", " ", $title), 80) }}
            </h3>
            @endisset

            <p class="card__body">
                {{ $slot }}
            </p>

            <span class="enpoints-counts">12 Endpoints @svg('eye')</span>
            
            @isset($countries)

            <p class="card__header__mini">Available in</p>

            <div class="country-selector">
                <div class="countries">
                    @foreach ($countries as $name => $country)
                        <img src="/images/locations/{{ $country }}.svg" title="{{ gettype($name) === 'string' ? $name : $country }} flag" alt="{{ $country }} flag">
                    @endforeach
                </div>

                @if (count($countries ) > 4)
                    <div class="view-more">+ {{count($countries )-1}}</div>
                @endif
            </div>

            @if (count($countries ) > 4)
                <div class="view-more-country-container">
                    @foreach ($countries as $name => $country)
                    <div class="each-country">
                        <img src="/images/locations/{{ $country }}.svg" title="{{ gettype($name) === 'string' ? $name : $country }} flag" alt="{{ $country }} flag">
                        <span>{{ $name }}</span>
                    </div>
                    @endforeach
                </div>
            @endif

            @endisset

        </div>
    </a>
    <div class="buttons">
        <a class="flex button dark outline" target="_blank" href="{{ $href }}" role="button">View product</a>
        @isset($addButtonId)
        <label class="flex button fab dark" for="{{ $addButtonId }}">
            @svg('plus', '#FFF')
            @svg('done', '#FFF')
        </label>
        @endisset
    </div>
</div>

</div>
