@once
@push('styles')
<link rel="stylesheet" href="{{ mix('/css/components/dialog-box.css') }}">
@endpush
@endonce
@props(['dialogTitle' => ''])

<div {{ $attributes->merge(['class' => 'mdp-dialog']) }} transparent>
    <div class="dialog-background" onclick="closeDialog(this);"></div>
    <div class="dialog-content">
        <h2 class="dialog-heading">{{ $dialogTitle }}</h2>
        <button type="button" class="dialog-close" onclick="closeDialog(this);">@svg('close')</button>
        <div class="dialog-content-inner">
            {!! $slot !!}
        </div>
    </div>
</div>

@once
@push('scripts')
<script src="{{ mix('/js/components/dialog.js') }}"></script>
@endpush
@endonce
