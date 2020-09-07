@extends('layouts.admin')

@section('title', $product->display_name)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/products/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/vendor/trix.css') }}">
<script src="{{ mix('/js/vendor/trix.js') }}"></script>
@endpush

@section('content')
<a class="button mb-2" href="{{ route('product.show', $product->slug) }}" target="_blank" rel="noreferrer">View Product</a>

<div id="uploader">
   @svg('upload')
   @svg('loading')
   <span>Upload Swagger</span>
   <div class="errors"></div>
</div>

<form id="edit-form" action="{{ route('admin.product.update', $product->slug) }}" method="POST">

    @method('PUT')
    @csrf

    <label>
        <h2>Overview</h2>
        <input type="hidden" name="content[{{ $product->slug }}-overview][title]" value="Overview">
        <input id="{{ $product->slug }}-overview-body" type="hidden" name="content[{{ $product->slug }}-overview][body]" value="{{ $content['Overview'][0]['body'] ?? '' }}">
        <trix-editor input="{{ $product->slug }}-overview-body"></trix-editor>
    </label>

    <label>
        <h2>Docs</h2>
        <input type="hidden" name="content[{{ $product->slug }}-docs][title]" value="Docs">
        <input id="{{ $product->slug }}-docs-body" type="hidden" name="content[{{ $product->slug }}-docs][body]" value="{{ $content['Docs'][0]['body'] ?? '' }}">
        <trix-editor input="{{ $product->slug }}-docs-body"></trix-editor>
    </label>

    <h2>Custom Tabs</h2>
    <p>Add custom tabs to the product.</p>

    @foreach($content as $title => $c)
    @if($title === 'Overview' || $title === 'Docs')
        @continue
    @endif
    <label for="{{ $c[0]['slug'] }}">
        <input type="text" name="content[{{ $c[0]['slug'] }}][title]" value="{{ $c[0]['title'] }}">
        <input id="{{ $c[0]['slug'] }}" type="hidden" name="content[{{ $c[0]['slug'] }}][body]" value="{{ $c[0]['body'] ?? '' }}">
        <trix-editor input="{{ $c[0]['slug'] }}"></trix-editor>
        <button class="dark small mt-1" onclick="removeTab(this)">Remove</button>
    </label>
    @endforeach
    @php
        $randId = Str::random(8);
    @endphp
    <div class="new-tab">
        <input type="text" name="tab[title][]" placeholder="Title">
        <input id="{{ $randId }}" type="hidden" name="tab[body][]">
        <trix-editor input="{{ $randId }}"></trix-editor>
        <button class="dark small mt-1" onclick="removeTab(this)">Remove</button>
    </div>

    <hr id="hr">
    <button id="new-tab" type="button">New Tab</button>
    <button>Update</button>

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