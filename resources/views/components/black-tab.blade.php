{{-- 
    This is component is just to have some mutliple options that are not removable or editable
    Just the display 
--}}

@props(['id', 'name', 'options', 'selected', 'label'])

@once
@push('styles')
<link rel="stylesheet" href="{{ mix('/css/components/multiselect.css') }}">
@endpush
@endonce

@php
    $id = $id ?? $name;
    $selected = $selected ?? [];
    $label = $label ?? '-- Select --';
    $selectOptions = "<option disabled selected value=\"\">$label</option>";
    $selectMultipleOptions = "<option disabled>$label</option>";
    $tags = "";
    $i = 1;
    foreach ($options as $key => $value) {
        $selectOptions .= "<option value=\"$key\">$value</option>";
        $selectMultipleOptions .= "<option value=\"$key\" " . (in_array($key, $selected) ? 'selected' : '') . ">$value</option>";
        if(in_array($key, $selected)){
            $tags .= '<span class="tag grey hoverable" data-index="' . $i . '" data-id="' . $id . '">' . $value . '</span>';
        }
        ++$i;
    }
@endphp

<select name="{{$name}}-select" id="{{$id}}-select" {{ $attributes->merge(['class' => 'multiselect-select']) }} onchange="multiselectChanged(this);" autocomplete="off">
    {!!$selectOptions!!}
</select>
<select name="{{$name}}[]" id="{{$id}}" class="multiselect" multiple autocomplete="off">
    {!!$selectMultipleOptions!!}
</select>
<div id="{{$id}}-tags" class="multiselect-tags" onclick="removeTag(event)">{!!$tags!!}</div>
