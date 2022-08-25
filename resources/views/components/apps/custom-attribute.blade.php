@props(['nameValue'=> '', 'valueValue'=>''])
@once
@push('styles')
<link rel="stylesheet" href="{{ mix('/css/components/custom-attribute.css') }}">
@endpush
@endonce

<div class="each-attribute-block">
    <input class="attribute-data name" name="attribute[name][]" data-original="{!! $nameValue !!}" value="{!! $nameValue !!}" onblur="this.value === '' && addAlert('error', 'Please complete the field')" onchange="removeQuote(this)"/>
    <input class="attribute-data value" name="attribute[value][]" data-original="{!! $valueValue !!}" value="{!! $valueValue !!}" onblur="this.value === '' && addAlert('error', 'Please complete the field')" onchange="removeQuote(this)"/>
    <button type="button" class="attribute-remove-btn" onclick="attributeRemove(this)">@svg('attribute-trash')</button>
</div>

@once
@push('scripts')
<script type="text/javascript" src="{{ mix('/js/components/custom-attribute.js') }}" defer></script>
@endpush
@endonce
