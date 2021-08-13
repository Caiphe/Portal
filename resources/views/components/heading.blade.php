{{--
    This component adds a heading to the page. It consists of the h1 tag as well as the ability
    to add tags, a fab button and content that will be in the far right of the heading.
    What is in the slot part of the component will be added to the right side of the heading.
    Eg:
    <x-heading heading="This is a heading" tags="tag 1,tag 2, tag 3">
        <button>hello world</button>
    </x-heading>
    or
    <x-heading heading="This is a heading" fab="dark plus">
        <div>Maybe a list of flags here...</div>
    </x-heading>

    heading: The content that will go into the h1.
    tags: A comma seperated list of tags.
    fab: The classes that will style the fab button
 --}}
@props(['heading', 'tags', 'fab', 'breadcrumbs' => [], 'edit', 'can' => 'view-admin'])
@php 
    $tags = isset($tags) ? preg_split('/,\s?/', $tags) : [] 
@endphp

@allowonce('heading')
@push('styles')
<link rel="stylesheet" href="{{ mix('/css/components/heading.css') }}">
@endpush
@endallowonce

<div id="heading">
    @if(!empty($breadcrumbs))
    <ul class="breadcrumbs">
        @foreach($breadcrumbs as $label => $href)
        <li class="breadcrumb"><a href="{{ $href }}">{{ $label }}</a></li>
        @endforeach
        @if(isset($edit) && \Auth::check() && \Auth::user()->can($can))
        <li class="breadcrumb edit-page"><a href="{{ $edit }}">Edit</a></li>
        @endif
    </ul>
    @endif
    <h1>
        {{ $heading }}
        @foreach($tags as $tag)
        <span class="tag outline grey">{{ $tag }}</span>
        @endforeach
    </h1>

    @if(isset($fab))
    <button id="heading-fab" class="fab {{ $fab }}"></button>
    @endif

    <div class="right-side">{{ $slot }}</div>
</div>
