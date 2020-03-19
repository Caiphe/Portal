@props(['id', 'name', 'scheme', 'options', 'selected', 'label'])

@allowonce('multiselect')
<link rel="stylesheet" href="/css/components/multiselect.css">
@endallowonce

@php
    $id = $id ?? $name;
    $selected = $selected ?? [];
    $label = $label ?? '-- Select --';
    $selectOptions = "<option disabled selected>$label</option>";
    $selectMultipleOptions = "<option disabled>$label</option>";
    $tags = "";
    $i = 1;
    foreach ($options as $key => $value) {
        $selectOptions .= "<option value=\"$key\">$value</option>";
        $selectMultipleOptions .= "<option value=\"$key\" " . (in_array($key, $selected) ? 'selected' : '') . ">$value</option>";
        if(in_array($key, $selected)){
            $tags = '<span class="tag grey hoverable removeable" data-index="' . $i . '" data-id="' . $id . '">' . $value . '</span>';
        }
        ++$i;
    }
@endphp

<select name="{{$name}}-select" id="{{$id}}-select" {{ $attributes->merge(['class' => 'multiselect-select ' . ' ' . ($scheme ?? 'dark')]) }} onchange="multiselectChanged(this);" autocomplete="off">
    {!!$selectOptions!!}
</select>
<select name="{{$name}}[]" id="{{$id}}" class="multiselect" multiple autocomplete="off">
    {!!$selectMultipleOptions!!}
</select>
<div id="{{$id}}-tags" class="multiselect-tags" onclick="removeTag(event)">{!!$tags!!}</div>

@pushscript('multiselect')
<script src="/js/components/multiselect.js" defer></script>
@endpushscript