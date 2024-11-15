@props(['slug', 'name', 'displayName', 'href'])

<div {{ $attributes->merge(['class' => 'product-block product-item card card--product display-cards']) }}>
    <input id="{{ $slug }}" type="checkbox" class="add-product" name="add_product[]" value="{{ $name }}" autocomplete="off">
    <span>{{ $displayName }}</span>
    <a href="{{ $href }}" target="_blank"><img src="/images/icons/external_link.png" alt="External Link to {{ $displayName }}"></a>
</div>
