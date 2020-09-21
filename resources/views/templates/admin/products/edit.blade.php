@extends('layouts.admin')

@section('title', $product->display_name)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/vendor/trix.css') }}">
<script src="{{ mix('/js/vendor/trix.js') }}"></script>
@endpush

@section('page-info')
    <a href="#overview" class="button outline dark ml-1">OVERVIEW</a>
    <a href="#docs" class="button outline dark ml-1">DOCS</a>
    <a href="#custom-tabs" class="button outline dark ml-1">CUSTOM TABS</a>
    <a href="{{ route('product.show', $product->slug) }}" target="_blank" rel="noreferrer" class="button outline dark ml-1">VIEW</a>
    <button id="save" class="outline dark ml-1" form="admin-form">SAVE</button>
@endsection

@section('content')

<div id="uploader">
   @svg('plus-circle-filled')
   @svg('loading-blue')
   <span>Upload Swagger</span>
   <div class="errors"></div>
</div>

<form id="admin-form" action="{{ route('admin.product.update', $product->slug) }}" method="POST">

    @method('PUT')
    @csrf

    <div class="editor-field">
        <h2>Category</h2>
        <select name="category_cid" id="category_cid" class="mb-1" autocomplete="off">
            <option value="" selected disabled="">Select category</option>
            @foreach($categories as $cid => $category)
            <option value="{{ $cid }}" @if($cid === $product->category_cid) selected @endif>{{ $category }}</option>
            @endforeach
        </select>
    </div>

    <div class="editor-field">
        <h2>Group</h2>
        <input type="text" name="group" value="{{ $product->group ?? 'MTN' }}">
    </div>

    <div class="editor-field">
        <h2>Locations</h2>
        <x-multiselect id="locations" name="locations" label="Select location" :options="$countries->pluck('name', 'code')->toArray()" :selected="$product->locations === 'all' ? [] : $product->countries()->pluck('code')->toArray()"/>
    </div>

    <div id="overview" class="editor-field">
        <h2>Overview</h2>
        <input type="hidden" name="tab[title][]" value="Overview">
        <input id="{{ $product->slug }}-overview-body" type="hidden" name="tab[body][]" value="{{ $content['Overview'][0]['body'] ?? '' }}">
        <trix-editor input="{{ $product->slug }}-overview-body"></trix-editor>
    </div>

    <div id="docs" class="editor-field">
        <h2>Docs</h2>
        <input type="hidden" name="tab[title][]" value="Docs">
        <input id="{{ $product->slug }}-docs-body" type="hidden" name="tab[body][]" value="{{ $content['Docs'][0]['body'] ?? '' }}">
        <trix-editor input="{{ $product->slug }}-docs-body"></trix-editor>
    </div>

    <div id="custom-tabs" class="editor-field">
        <h2>Custom Tabs</h2>
        <p class="mb-0">Add custom tabs to the product.</p>

        @foreach($content as $title => $c)
        @if($title === 'Overview' || $title === 'Docs')
            @continue
        @endif
        <div class="old-tab mt-3">
            <button class="dark outline mt-1" onclick="removeTab(this)">Remove</button>
            <input type="text" name="content[{{ $c[0]['slug'] }}][title]" value="{{ $c[0]['title'] }}">
            <input id="{{ $c[0]['slug'] }}" type="hidden" name="content[{{ $c[0]['slug'] }}][body]" value="{{ $c[0]['body'] ?? '' }}">
            <trix-editor input="{{ $c[0]['slug'] }}"></trix-editor>
        </div>
        @endforeach
        @php
            $randId = Str::random(8);
        @endphp
        <div class="new-tab mt-3">
            <button class="dark outline" onclick="removeTab(this)">Remove</button>
            <input type="text" name="tab[title][]" placeholder="Title">
            <input id="{{ $randId }}" type="hidden" name="tab[body][]">
            <trix-editor input="{{ $randId }}"></trix-editor>
        </div>
    </div>

    <button id="new-tab" type="button">@svg('plus-circle-filled')<br>NEW CUSTOM TAB</button>

</form>
@endsection

@push('scripts')
<script>
    function bladeLookup(key) {
        return {
            openApiUrl: "{{ route('api.product.openapi.upload', $product->slug) }}",
            uploadImageUrl: "{{ route('api.product.image.upload', $product->slug) }}"
        }[key] || null;
    }
</script>
<script src="{{ mix('/js/templates/admin/products/edit.js') }}" defer></script>
@endpush