@props(['slug', 'name', 'displayName', 'href', 'selectedProducts' => []])

<div {{ $attributes->merge(['class' => 'product-block product-item card card--product display-cards']) }}>
    <input
        id="{{ $slug }}"
        type="checkbox"
        class="add-product"
        name="add_product[]"
        value="{{ $name }}" autocomplete="off"
        @if(isset($selectedProducts) && in_array($name, $selectedProducts)) checked @endif
    />
    <span>{{ $displayName }}</span>
    <a href="{{ $href }}" target="_blank"><img src="/images/open-tab.svg" alt="External Link to {{ $displayName }}"></a>
</div>
