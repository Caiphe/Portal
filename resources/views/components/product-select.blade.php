@props(['slug', 'name', 'displayName'])

<div {{ $attributes->merge(['class' => 'product-block product-item card card--product']) }}>
    <input id="{{ $slug }}" type="checkbox" class="add-product" name="add_product[]" value="{{ $name }}" autocomplete="off">
    <label for="{{ $slug }}">{{ $displayName }}</label>
</div>
