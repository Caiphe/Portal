{{--
    This component allows for adding icon cards.
    Eg:
    <x-card-icon target="_blank" icon="twitter" title="My Apps" href="http://www.google.com">This is the title</x-card-icon>
    icon -  is the icon name for the icon on the card e.g. twitter
	title - the card title
--}}

@once
@push('styles')
<link href="{{ mix('/css/components/card-icon.css') }}" rel="stylesheet"/>
@endpush
@endonce

@props(['title', 'icon', 'href'])

<a {{ $attributes }} href="{{$href}}">
	<div class="card-icon">
		@isset($icon)
			@svg($icon, '#000000')
		@endisset
		@isset($title)
            <p class="card__header">
                {{ $title }}
            </p>
        @endisset
	</div>
</a>