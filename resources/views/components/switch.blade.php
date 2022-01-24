@props(['id' => 'switch', 'name' => 'switch', 'value' => '', 'type' => 'big', 'scheme' => 'dark', 'checked' => ''])

<label id="{{ isset($id) ? "{$id}-label" : 'switch-label' }}" for="{{ $id }}" {{ $attributes->merge(['class' => "switch {$type} {$scheme}"]) }}>
    <input type="checkbox" name="{{ $name }}" id="{{ $id ?? $name ?? 'switch' }}" value="{{ $value }}" {{ $checked }} autocomplete="off">
    <span class="track"></span>
    <span class="ball"></span>
    @if(!empty(trim($slot)))
    <span class="swatch-label">{{ $slot }}</span>
    @endisset
</label>