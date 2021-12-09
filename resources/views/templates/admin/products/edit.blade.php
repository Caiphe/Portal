@extends('layouts.admin')

@section('title', $product->display_name)
@section('body-class', 'product-edit')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/templates/admin/products/edit.css') }}">
@endpush

@section('content')
<a href="{{ url()->previous() }}" class="go-back">@svg('chevron-left') Back to Products</a>
<h1>{{ $product->display_name }}</h1>

<div class="page-actions">
    <a href="{{ route('product.show', $product->slug) }}" target="_blank" rel="noreferrer" class="button outline dark ml-1">View Product</a>
    @if($product->swagger)
        <a class="button outline dark" href="/openapi/{{ $product->swagger }}" download>Download Swagger</a>
    @endif
    <label id="uploader">
        @svg('loading-blue')
        <span>Upload Swagger</span>
        <input id="uploader-input" type="file" name="uploader" hidden accept=".yaml,yml">
    </label>
    <button id="save" class="button primary ml-1" form="admin-form">Save</button>
</div>


<form id="admin-form" action="{{ route('admin.product.update', $product->slug) }}" method="POST">
    @method('PUT')
    @csrf

    <div class="editor-field">
        <h2>Display name</h2>
        <input type="text" name="display_name" value="{{ $product->display_name }}" maxlength="140">
    </div>

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
        <div class="editor" data-input="{{ $product->slug }}-overview-body" data-name="tab[body][]">{!! $content['Overview'][0]['body'] ?? '' !!}</div>
    </div>

    <div id="docs" class="editor-field">
        <h2>Docs</h2>
        <input type="hidden" name="tab[title][]" value="Docs">
        <div class="editor" data-input="{{ $product->slug }}-docs-body" data-name="tab[body][]">{!! $content['Docs'][0]['body'] ?? '' !!}</div>
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
            <input class="custom-tab-title" type="text" name="tab[title][]" value="{{ $c[0]['title'] }}">
            <div class="editor" data-input="{{ $c[0]['slug'] }}" data-name="tab[body][]" data-class="custom-tab-content">{!! $c[0]['body'] ?? '' !!}</div>
        </div>
        @endforeach
        @php
            $randId = Str::random(8);
        @endphp
        <div class="new-tab mt-3">
            <button class="dark outline" onclick="removeTab(this)">Remove</button>
            <input class="custom-tab-title" type="text" name="tab[title][]" placeholder="Title">
            <div class="editor" data-input="{{ $randId }}" data-name="tab[body][]" data-class="custom-tab-content"></div>
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
<script src="{{ mix('/js/components/ckeditor.js') }}" defer></script>
<script src="{{ mix('/js/templates/admin/products/edit.js') }}" defer></script>
@endpush