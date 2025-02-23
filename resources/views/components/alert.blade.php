@push('styles')
<link rel="stylesheet" href="{{ mix('/css/components/alert.css') }}">
@endpush

@if ((isset($errors) && $errors->any()) || Session::has('alert'))
@php
if($errors->any()){
    $type = 'error';
    $messages = $errors->all();
} else {
    $alert = explode(':', Session::get('alert'));
    $type = 'success';
    $messages = $alert[0];
    if(count($alert) > 1){
        $type = strtolower($alert[0]);
        $messages = $alert[1];
    }
    $messages = preg_split('/;\s?/', $messages);
}
@endphp
<div id="alert" class="{{$type}} open">
    <ul>
        @foreach ($messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
    <button class="fab blue close" onclick="closeAlert()">&times;</button>
</div>
@endif

@push('scripts')
<script src="{{ mix('/js/components/alert.js') }}" defer></script>
@endpush
