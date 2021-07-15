@once
@push('styles')
<link rel="stylesheet" href="{{ mix('/css/components/dialog.css') }}">
@endpush
@endonce

<div class="mdp-dialog" {{ $attributes }}>
    <div class="dialog-background close-dialog"></div>
    <div class="dialog-content">
        <button class="dialog-close close-dialog">&times;</button>
        {!! $slot !!}
    </div>
</div>

@once
@push('scripts')
<script src="{{ mix('/js/components/dialog.js') }}"></script>
@endpush
@endonce