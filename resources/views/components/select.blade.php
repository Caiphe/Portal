@allowonce('card_product')
<link href="{{ mix('/css/components/_select.css') }}" rel="stylesheet"/>
@endallowonce

<select name="countries" id="countries">
    <option value="" disabled selected>Select country</option>
    <option value="Cameroon">
        Cameroon
    </option>
</select>

@pushscript('select')
<script src="{{ mix('/js/components/select.js') }}" defer></script>
@endpushscript


