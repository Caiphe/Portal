@extends('layouts.admin')

@section('title', "Edit {$product->display_name}")

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/products/edit.css') }}">
@endpush

@section('content')
<form id="edit-form" action="{{ route('product.store', $product->slug) }}" method="POST" enctype="multipart/form-data">

    @method('PUT')
    @csrf

    <label>
        <h2>Upload Swagger</h2>
        <input type="file" name="swagger">
    </label>

    <label for="{{ $product->slug }}-overview">
        <h2>Overview</h2>
        <input type="hidden" name="content[{{ $product->slug }}-overview][title]" value="Overview">
        <textarea name="content[{{ $product->slug }}-overview][body]" id="{{ $product->slug }}-overview">{{ $content['Overview'][0]['body'] ?? '' }}</textarea>
    </label>

    <label for="{{ $product->slug }}-docs">
        <h2>Docs</h2>
        <input type="hidden" name="content[{{ $product->slug }}-docs][title]" value="Docs">
        <textarea name="content[{{ $product->slug }}-docs][body]" id="{{ $product->slug }}-docs">{{ $content['Docs'][0]['body'] ?? '' }}</textarea>
    </label>

    @foreach($content as $title => $c)
    @if($title === 'Overview' || $title === 'Docs')
        @continue
    @endif
    <label for="{{ $c[0]['slug'] }}">
        <input type="text" name="content[{{ $c[0]['slug'] }}][title]" value="{{ $c[0]['title'] }}">
        <textarea name="content[{{ $c[0]['slug'] }}][body]" id="{{ $c[0]['slug'] }}">{{ $c[0]['body'] ?? '' }}</textarea>
    </label>
    @endforeach

    <hr id="hr">
    <button id="new-tab" type="button">New Tab</button>
    <button>Update</button>

</form>
@endsection

@push('scripts')
<script src="{{ mix('/js/templates/admin/products/edit.js') }}" defer></script>
@endpush