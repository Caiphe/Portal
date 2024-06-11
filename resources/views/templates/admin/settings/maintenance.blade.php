@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/settings/maintenance.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('title', 'settings')

@section('content')
    <h1>Settings</h1>

    <form id="maintenance-form" action="{{ route('admin.maintenance.store') }}" method="POST">
        @csrf

        <div class="editor-field">
            <h2>Maintenance banner</h2>
            <p class="editor-field-label">Please disable previous maintenance notification before creating a new one.</p>
            <div class="editor-field-label" id="radio-base-container">
                <h3>Enable the maintenance banner?</h3>
                <div class="radio-container">

                    <label class="container">Enabled
                        <input type="radio" value="enabled" name="maintenance" @if($maintenanceData) {{ ($maintenanceData->enabled == "1")? "checked" : "" }} @endif />
                        <span class="checkmark"></span>
                    </label>

                    <label class="container">Disabled
                        <input type="radio" name="maintenance" value="disabled" @if($maintenanceData) {{ ($maintenanceData->enabled == "0")? "checked" : "" }} @endif />
                        <span class="checkmark"></span>
                    </label>

                </div>
            </div>
        
            <div class="editor-field-label">
                <h3>Banner text</h3>
                <textarea name="message" id="message" class="message" placeholder="Please insert a text message for the maintenance banner to display">@if($maintenanceData) {{ $maintenanceData->message }} @endif</textarea>
            </div>

            <button class="button black outline blue maintenance-button" type="submit">Save changes</button>
        </div>
    </form>

@endsection

@push('scripts')
<script src="{{ mix('/js/templates/admin/settings/maintenance.js') }}" defer></script>
@endpush
