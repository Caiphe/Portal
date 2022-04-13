@once
@push('styles')
<link rel="stylesheet" href="{{ mix('/css/components/dialog.css') }}">
@endpush
@endonce

<div {{ $attributes->merge(['class' => 'mdp-dialog']) }} transparent>
    <div class="dialog-background" onclick="closeDialog(this);"></div>
    <div class="dialog-content">
        <button class="dialog-close" onclick="closeDialog(this);">@svg('custom-close')</button>
        {!! $slot !!}
    </div>
</div>

@once
@push('scripts')
<script src="{{ mix('/js/components/dialog.js') }}"></script>
@endpush
@endonce
