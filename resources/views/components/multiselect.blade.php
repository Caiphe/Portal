{{-- 
    This component allows for adding multiselects that show removeable tags.
    If you would like to add an event listener for the change please use the 'multiselect' event, like the below:
    el.addEventListener('mulstiselct', function);
    Eg:
    <x-multiselect id="test" name="test" label="please choose it" :options="['first' => 'one', 'second' => 'two']" :selected="['first']"/>
    
    id: The id that gets passed in. This needs to be a prop because I reuse it in a couple places
    name: The name of the select. I use this as a fallback if no id is added
    label: What should be the first first option that tells the user what to do, defaults to -- Select --
    options: An associative array that makes up the options. The key is the value and the value is the option label. I know that sounds weird :)
    selected: An array that will mark an option as selected. This is a single array of values.
--}}

@props(['id', 'name', 'options', 'selected', 'label'])

@allowonce('multiselect')
<link rel="stylesheet" href="{{ mix('/css/components/multiselect.css') }}">
@endallowonce

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
            $tags .= '<span class="tag grey hoverable removeable" data-index="' . $i . '" data-id="' . $id . '">' . $value . '</span>';
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

@pushscript('multiselect')
<script src="{{ mix('/js/components/multiselect.js') }}" defer></script>
@endpushscript