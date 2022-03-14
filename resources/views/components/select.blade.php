@once
@push('styles')
<link href="{{ mix('/css/components/_select.css') }}" rel="stylesheet"/>
@endpush
@endonce

<select class="select" name="countries" id="countries">
    <option value="" disabled>Select country</option>
    <option value="Benin" data-skype="https://join.skype.com/IcqSeZv5addB" data-whatsapp="https://chat.whatsapp.com/H8SSUPlfnj30SWWAiXvw3S">
        Benin
    </option>
    <option value="Cameroon" data-skype="https://join.skype.com/aGxvVctk6p6m" data-whatsapp="https://wa.me/237683008787">
        Cameroon
    </option>
    <option value="Congo" data-skype="https://join.skype.com/Miylc7eTTq4D" data-whatsapp="https://chat.whatsapp.com/LsR5weBDCuT6rx5MamnsLf">
        Congo
    </option>
    <option value="Ghana" data-skype="https://join.skype.com/cgNGP80mWRIN" data-whatsapp="https://chat.whatsapp.com/H3nqtWl6eAo471IYITryto">
        Ghana
    </option>
    <option value="Ivory Coast" data-skype="https://join.skype.com/nplpawYhx1A7" data-whatsapp="https://chat.whatsapp.com/H9MHBffzks77xrkWmuPd2c">
        Ivory Coast
    </option>
    <option value="Uganda" data-skype="https://join.skype.com/o8kbc3adA4Td" data-whatsapp="https://chat.whatsapp.com/C3ggW9d0L7o3uoe2A9Ck9A">
        Uganda
    </option>
    <option value="Zambia" data-skype="https://join.skype.com/efBnUreVQWIe" data-whatsapp="https://chat.whatsapp.com/IIwYgvY0rqO0wFtmGVgCQe">
        Zambia
    </option>
</select>

@once
@push('scripts')
<script src="{{ mix('/js/components/select.js') }}" defer></script>
@endpush
@endonce


