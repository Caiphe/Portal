@extends('layouts.admin')

@section('title', $product->display_name)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/templates/admin/products/edit.css') }}">
@endpush

@section('content')
<a href="{{ route('admin.product.index') }}" class="go-back">@svg('chevron-left') Back to products</a>
<h1>{{ $product->display_name }}</h1>

<div class="page-actions">
    <a href="{{ route('product.show', $product->slug) }}" target="_blank" rel="noreferrer" class="button outline dark ml-1">View product</a>
    <a @class([
        'button',
        'outline',
        'dark',
        'no-swagger' => !$hasSwagger
    ]) href="/openapi/{{ $product->swagger }}" download>Download swagger</a>
    <label id="uploader">
        @svg('loading-blue')
        <span>{{ $hasSwagger ? 'Replace' : 'Upload' }} swagger</span>
        <input id="uploader-input" type="file" name="uploader" hidden accept=".yaml,yml">
    </label>
</div>

<form id="admin-form" action="{{ route('admin.product.update', $product->slug) }}" method="POST">
    @method('PUT')
    @csrf

    <div class="editor-field one-third">
        <h2>Product details</h2>

        <label class="editor-field-label">
            <h3>Display name</h3>
            <input type="text" name="display_name" value="{{ $product->display_name }}" maxlength="140">
        </label>

        <label class="editor-field-label">
            <h3>Category</h3>
            <select name="category_cid" id="category_cid" class="mb-1" autocomplete="off">
                <option value="" selected disabled="">Select category</option>
                @foreach($categories as $cid => $category)
                <option value="{{ $cid }}" @if($cid === $product->category_cid) selected @endif>{{ $category }}</option>
                @endforeach
            </select>
        </label>

        <label class="editor-field-label">
            <h3>Group</h3>
            <input type="text" name="group" value="{{ $product->group ?? 'MTN' }}">
        </label>

        <label class="editor-field-label">
            <h3>Locations</h3>
            <x-multiselect id="locations" name="locations" label="Select location" :options="$countries->pluck('name', 'code')->toArray()" :selected="$product->locations === 'all' ? [] : $product->countries()->pluck('code')->toArray()"/>
        </label>

        <button class="button outline blue save-button">Apply changes</button>
    </div>

    <div id="custom-tabs" class="editor-field two-thirds">
        <h2>Content details</h2>

        <div class="editor-field-label">
            <h3>Overview</h3>
            <input type="hidden" name="tab[title][]" value="Overview">
            <div class="editor" data-input="{{ $product->slug }}-overview-body" data-name="tab[body][]">{!! $content['Overview'][0]['body'] ?? '' !!}</div>
        </div>

        <div class="editor-field-label">
            <h3>Docs</h3>
            <input type="hidden" name="tab[title][]" value="Docs">
            <div class="editor" data-input="{{ $product->slug }}-docs-body" data-name="tab[body][]">{!! $content['Docs'][0]['body'] ?? '' !!}</div>
        </div>

        <div class="editor-field-label">
            <h3>Custom tabs</h3>
            @foreach($content as $title => $c)
            @if($title === 'Overview' || $title === 'Docs')
                @continue
            @endif
            <div class="custom-tab old-tab">
                <input class="custom-tab-title" type="text" name="tab[title][]" value="{{ $c[0]['title'] }}" placeholder="Custom tab title">
                <div class="editor" data-input="{{ $c[0]['slug'] }}" data-name="tab[body][]" data-class="custom-tab-content">{!! $c[0]['body'] ?? '' !!}</div>
                <button type="button" class="remove-custom-tab sl-button" onclick="removeTab(this)" aria-label="Remove custom tab">@svg('minus-circle-outline')</button>
            </div>
            @endforeach

            <div class="custom-tab new-tab">
                <input class="custom-tab-title" type="text" name="tab[title][]" placeholder="Custom tab title" autocomplete="off">
                <div class="editor" data-input="{{ Str::random(8) }}" data-name="tab[body][]" data-class="custom-tab-content"></div>
                <button type="button" class="remove-custom-tab sl-button" onclick="removeTab(this)" aria-label="Remove custom tab">@svg('minus-circle-outline')</button>
            </div>

            <button id="add-custom-tab" type="button" class="add-custom-tab sl-button" aria-label="Add new custom tab">@svg('add-circle-outline')</button>
        </div>

        <button class="button outline blue save-button">Apply changes</button>
    </div>
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
<script src="{{ mix('/js/components/ckeditor.js') }}" defer></script>
@endpush