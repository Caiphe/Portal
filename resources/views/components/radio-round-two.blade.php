@props(['name', 'value', 'id', 'checked' => false])

@php
    $id ??= Str::slug($name);
@endphp

@once
@push('styles')
<link rel="stylesheet" href="{{ mix('/css/components/checkbox-round.css') }}">
@endpush
@endonce

<label class="radio-round">
    <input type="radio" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}" autocomplete="off" @if($checked) checked @endif>
    <span class="unchecked"></span><span class="checked"></span>
</label>
