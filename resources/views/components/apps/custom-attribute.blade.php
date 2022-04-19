@once
@push('styles')
<link rel="stylesheet" href="{{ mix('/css/components/custom-attribute.css') }}">
@endpush
@endonce

<div class="each-attribute-block">
    <input class="attribute-data name" value=""/>
    <input class="attribute-data value" value=""/>
    <button type="button" class="attribute-remove-btn">@svg('attribute-trash')</button>
</div>
