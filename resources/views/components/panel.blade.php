@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/components/_panel.css') }}">
@endpush

<div class="panel">
    {{ $slot }}
</div>
