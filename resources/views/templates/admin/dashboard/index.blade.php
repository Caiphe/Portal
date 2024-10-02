@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/dashboard/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/dashboard/custom-attribute.css') }}">

@endpush

@section('title', 'Applications')

@section('content')
@if(!is_null($previous))
<a href="{{ $previous }}" class="go-back">@svg('chevron-left') Back to user</a>
@endif
<h1> Applications </h1>

<div class="page-actions">
    <a class="button primary page-actions-create" href="{{ route('admin.app.create') }}" aria-label="Create new application"></a>
</div>

<x-admin.filter searchTitle="App name">
    <div class="filter-item">
        Product status
        <select id="product-filter-status" name="product-status" autocomplete="off">
            <option @if($productStatus === 'all') selected @endif value="all">All product status</option>
            <option @if($productStatus === 'pending') selected @endif value="pending">Pending apps</option>
            <option @if($productStatus === 'all-approved') selected @endif value="all-approved">All approved</option>
            <option @if($productStatus === 'at-least-one-approved') selected @endif value="at-least-one-approved">At least one approved</option>
            <option @if($productStatus === 'all-revoked') selected @endif value="all-revoked">All revoked</option>
            <option @if($productStatus === 'at-least-one-revoked') selected @endif value="at-least-one-revoked">At least one revoked</option>
        </select>
    </div>

    <div class="filter-item">
        Country list
        <select id="filter-country"  name="countries" label="Select country" autocomplete="off">
            <option value="">All countries</option>
            @foreach($countries as $code => $name)
                <option value="{{ $code }}" {{ (($selectedCountry === $code) ? 'selected': '') }}>{{ $name }}</option>
            @endforeach
        </select>
    </div>
</x-admin.filter>

<div id="table-data">
    @include('templates.admin.dashboard.data')
</div>

<x-dialog-box class="admin-removal-confirm" id="status-dialog">
    <form class="status-dialog-form" name="status-note-form" method="POST" action="">
        <div class="data-container">
            @csrf
            <input class="app-dialog-status" type="hidden" value="approved" name="status">
            <textarea class="status-dialog-textarea" name="status-note" rows="5" placeholder="Optional product status change note" autocomplete="off"></textarea>
        </div>

        <div class="bottom-shadow-container button-container">
            <button class="status-dialog-button">Submit</button>
        </div>
    </form>
</x-dialog-box>

<template id="custom-attribute" hidden>
    <x-apps.custom-attribute></x-apps.custom-attribute>
</template>
@endsection

@push('scripts')
<script src="{{ mix('/js/templates/admin/dashboard/index.js') }}" defer></script>
<script src="{{ mix('/js/templates/admin/dashboard/app-custom-attribute.js') }}" defer></script>
<script src="{{ mix('/js/templates/admin/dashboard/app-edit-custom-attribute.js') }}" defer></script>
<script src="{{ mix('/js/templates/admin/dashboard/app-reseved-custom-attribute.js') }}" defer></script>
@endpush
