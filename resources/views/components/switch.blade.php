<label {{ $attributes->merge(['class' => 'switch '.($type ?? 'big')]) }}>
    <input type="checkbox" name="{{$name}}" id="{{$id}}" value="{{$value ?? ''}}">
    <span class="ball"></span>
</label>