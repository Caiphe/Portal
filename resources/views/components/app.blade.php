@props(['title'])

<div class="app" {{ $attributes }}>
    @isset($title)
        {{ $title }}
    @endisset
</div>
