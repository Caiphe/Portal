@props(['nameValue'=> '', 'valueValue'=>''])
@once
@push('styles')
<link rel="stylesheet" href="{{ mix('/css/components/custom-attribute.css') }}">
@endpush
@endonce

<div class="each-attribute-block">
    <input class="attribute-data name" name="attribute[name][]" value="{{ $nameValue }}"/>
    <input class="attribute-data value" name="attribute[value][]" value="{{ $valueValue }}"/>
    <button type="button" class="attribute-remove-btn" onclick="attributeRemove(this)">@svg('attribute-trash')</button>
</div>


@once
@push('scripts')
<script type="text/javascript">
    function attributeRemove(button){
        var attribute = button.parentNode;
        attribute.parentNode.removeChild(attribute);

        if(document.querySelectorAll('.each-attribute-block').length === 0){
            var addedAttributeForm =  document.querySelector('.custom-attribute-list-container');
            document.querySelector('.attributes-heading').classList.remove('show');
            addedAttributeForm.classList.remove('active');
            addedAttributeForm.classList.add('non-active');
        }
    }
</script>
@endpush
@endonce
