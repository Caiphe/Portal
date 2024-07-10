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

     @can('administer-content')
    <label id="uploader">
        @svg('loading-blue')
        <span>{{ $hasSwagger ? 'Replace' : 'Upload' }} swagger</span>
        <input id="uploader-input" type="file" name="uploader" hidden accept=".yaml,.yml,.json">
    </label>
    @endcan
    
</div>

<form id="admin-form" action="{{ route('admin.product.update', $product->slug) }}" method="POST">
    @method('PUT')
    @csrf

    <div class="one-third" id="main-product-details">
        <div class="editor-field product-details">
            <h2>Product details</h2>

            <label class="editor-field-label-product">
                <p class="product-heading">Display name</p>
                <span class="product-value">{{ $product->display_name }}</span>
            </label>

            <label class="editor-field-label-product">
                <p class="product-heading">Group</p>
                <span class="product-value">@if($product->group){{ $product->group}} @else MTN @endif</span>
            </label>

            <label class="editor-field-label-product locations-list">
                <p class="product-heading">Locations</p>
                <span class="product-value">
                    @foreach ($countries as $country) {{ $country }} <br /> @endforeach
                </span> 
            </label>
        </div>

    </div>

    <div id="custom-tabs" class="editor-field two-thirds">
        <h2>Product Overview</h2>

        <label class="editor-field-label">
            <h3>Product Description</h3>
            <textarea id="product-description" placeholder="Enter a description for this product" name="description" id="description">{{ $product->description }}</textarea>
        </label>

        <label class="editor-field-label">
            <h3>Category</h3>
            <select name="category_cid" id="category_cid" class="mb-1" autocomplete="off" />
                <option value="" selected disabled="">Select category</option>
                @foreach($categories as $cid => $category)
                <option value="{{ $cid }}" @if($cid === $product->category_cid) selected @endif>{{ $category }}</option>
                @endforeach
            </select>
        </label>

        <div class="editor-field-label over-view-block">
            <h3>Overview</h3>
            <input type="hidden" name="tab[title][]" value="Overview">
            <div class="editor" data-input="{{ $product->slug }}-overview-body" data-name="tab[body][]">{!! $content['Overview'][0]['body'] ?? '' !!}</div>
        </div>
        <button class="button outline blue save-button">Apply changes</button>
    </div>

    <div class="editor-field">
        <h2>Docs</h2>

        <div class="editor-field-label">
            <input type="hidden" name="tab[title][]" value="Docs">
            <div class="editor" data-input="{{ $product->slug }}-docs-body" data-name="tab[body][]">{!! $content['Docs'][0]['body'] ?? '' !!}</div>
        </div>

        <button class="button outline blue save-button">Apply changes</button>
    </div>

    @if(count($logs) > 0)
    <div class="editor-field">
       <h2>Product update log</h2>

        <div class="editor-field-label">
            @foreach ($logs as $log)
            <div class="each-log-block">
                <div class="log-container">
                    <div class="date-logged">
                        <span class="date-only">{{ date('j F Y', strtotime($log->created_at)) }}</span>
                        <span class="">{{ date('H:i', strtotime($log->created_at)) }}</span>
                    </div>
                    <div class="logged-description">{!! $log->message !!}</div>
                </div>
                <div class="logged-by">
                    <span class="span-by">by</span> 
                    <span class="developer-name">{{ $log->user->full_name }}</span>
                </div>

            </div>
            @endforeach
        </div>
    </div>
    @endif

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
