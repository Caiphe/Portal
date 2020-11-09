@extends('layouts.sidebar')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/apps/kyc.css') }}">
@endpush

@section('sidebar')
    <x-sidebar-accordion id="sidebar-accordion" :list="
    [ 'Manage' =>
        [
            [ 'label' => 'Profile', 'link' => '/profile'],
            [ 'label' => 'My apps', 'link' => '/apps'],
        ],
        'Discover' =>
        [
            [ 'label' => 'Browse all products', 'link' => '/products'],
            [ 'label' => 'Working with our products','link' => '/getting-started'],
        ]
    ]
    " />
@endsection

@section('title', "KYC Form - $group")

@section('content')
<x-heading heading="Go live" :tags="'KYC - ' . $group"></x-heading>
<div class="app-details">
    <strong>{{ $app->display_name }}</strong><br>
    <strong>Go live</strong> - Add KYC information
</div>
<div class="content">
    <form action="{{ route('app.kyc.store', ['app' => $app->aid, 'group' => $group]) }}" method="POST">
        @csrf

        <h2>About business owner</h2>
        <input class="long" type="text" name="name" placeholder="Full name" autocomplete="off">
        <input class="long" type="text" name="name" placeholder="National ID, Passport number" autocomplete="off">
        <div class="cols column">
            <input class="mr-1" type="text" name="number" placeholder="Phone number" autocomplete="off">
            <input type="text" name="email" placeholder="Email" autocomplete="off">
        </div>

        <h2>About the business</h2>
        <input class="long" type="text" name="business_name" placeholder="Business name" autocomplete="off">
        <select id="business-type" class="long" name="business_type" autocomplete="off">
            <option value="" selected disabled>Business type</option>
            @foreach($options['businessTypes'] as $option)
            <option value="{{ Str::slug($option['label']) }}">{{ $option['label'] }}</option>
            @endforeach
        </select>
        <textarea class="long" name="business_description" rows="10" placeholder="Description of your business" autocomplete="off"></textarea>

        <h2>KYC contracting and file management</h2>
        <p class="bold">Download Contracting Requirements</p>
        <p>Fill and sign these files and then upload them again in the field below</p>
        <a href="{{ $options['pdf'] }}" class="button" download>@svg("download") Download application forms</a>

        <p class="bold mt-3">Upload Signed Contracting Requirements</p>
        <p>Upload all documents as a pdf file only</p>
        <label class="file-upload button" for="signed-contracting-requirements">
            @svg("upload") Upload Signed Application Forms
            <input type="file" name="signed_contracting_requirements" id="signed-contracting-requirements" accept=".pdf">
        </label>

        @foreach($options['businessTypes'] as $businessType)
        <div id="{{ Str::slug($businessType['label']) }}" class="business-type">
            @foreach($businessType['kycChecklist'] as $kycChecklistItem)
            <div class="kyc-checklist-item mt-3">
                {!! $kycChecklistItem['label'] !!}
                <input type="hidden" name="{{ Str::slug($kycChecklistItem['value']) }}_value" value="{{ $kycChecklistItem['value'] }}">
                <label class="button file-upload">
                    @svg('upload') Upload
                    <input type="file" name="{{ Str::slug($kycChecklistItem['value']) }}" accept=".pdf">
                </label>
            </div>
            @endforeach
        </div>
        @endforeach

        <label for="accept" class="accept mt-3">
            <input type="checkbox" name="accept" id="accept" required>
            I/We certify that the information and supporting documentation provided in this application is true and accurate, and that I/We commit to delivering the physical KYC documents to MTN {{ $app->country->name }} within 7 days after submission. Failure to comply will lead to the service being suspended. I/We also accept to be to bound by the MTN Open API <a href="{{ $options['apiTermsAndConditions'] }}" target="_blank">Terms and Conditions</a>.
        </label>

        <button class="button dark mt-3">Submit @svg("arrow-forward", "#FFFFFF")</button>
    </form>
    </div>
@endsection

@push('scripts')
<script src="{{ mix('/js/templates/apps/kyc.js') }}"></script>
@endpush