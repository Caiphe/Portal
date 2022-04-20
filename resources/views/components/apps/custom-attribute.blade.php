@props(['nameValue'=> '', 'valueValue'=>''])
@once
@push('styles')
<link rel="stylesheet" href="{{ mix('/css/components/custom-attribute.css') }}">
@endpush
@endonce

<div class="each-attribute-block">
    <input class="attribute-data name" name="attribute[name][]" value="{{ $nameValue }}"/>
    <input class="attribute-data value" name="attribute[value][]" value="{{ $valueValue }}"/>
    <button type="button" class="attribute-remove-btn" onclick="attributeRemove">@svg('attribute-trash')</button>
</div>

@push('scripts')
<script type="text/javascript">
    function attributeRemove(){
        var attribute = this.parentNode;
        attribute.parentNode.removeChild(attribute);

        if(document.querySelectorAll('.each-attribute-block').length === 0){
            document.querySelector('.no-attribute').classList.remove('hide');
            document.querySelector('.attributes-heading').classList.remove('show');
        }
    }
</script>
@endpush
