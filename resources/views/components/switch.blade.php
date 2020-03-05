@props(['id', 'name', 'value', 'type', 'scheme'])

<label id="{{isset($id) ? $id.'-label' : 'switch-label'}}" for="{{$id ?? 'switch'}}" {{ $attributes->merge(['class' => 'switch '.($type ?? 'big') . ' ' . ($scheme ?? 'dark')]) }}>
    <input type="checkbox" name="{{$name ?? 'switch'}}" id="{{$id ?? $name ?? 'switch'}}" value="{{$value ?? ''}}">
    <span class="track"></span>
    <span class="ball"></span>
</label>