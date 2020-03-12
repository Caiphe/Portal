@props(['id', 'name', 'scheme', 'options'])

@allowonce('multiselect')
<style>
    .multiselect-tags {
        margin-top: 4px;
    }
    .tag{
        border: 1px solid #000;
        border-radius: 4px;
        background-color: #000;
        color: #FFF;
        padding: 2px 6px;
        font-size: 1rem;
        display: inline-flex;
        justify-content: center;
        cursor: pointer;
        margin-right: 4px;
    }
    .tag.removeable::after{
        content: "×";
        margin-left: 6px;
        font-size: 1.2rem;
    }
</style>
@endallowonce

<select name="{{$name}}" id="{{$id ?? $name}}" {{ $attributes->merge(['class' => 'multiselect ' . ' ' . ($scheme ?? 'dark')]) }} onchange="multiselectChanged(this);" autocomplete="off">
    <option value="" disabled selected>-- Select --</option>
    @foreach($options as $value => $label)
    <option value="{{$value}}">{{$label}}</option>
    @endforeach
</select>
<div id="{{$id ?? $name}}-tags" class="multiselect-tags"></div>

@pushscript('multiselect')
<script src="/js/components/multiselect.js" defer></script>
@endpushscript