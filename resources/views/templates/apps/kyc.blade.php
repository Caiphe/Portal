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

@section('title', "KYC Form - $for")

@section('content')
<x-heading heading="Go live" :tags="'KYC - ' . $for"></x-heading>
<div class="app-details">
    <strong>{{ $app->display_name }}</strong><br>
    <strong>Go live</strong> - Add KYC information
</div>
<div class="content">
    <form action="{{ route('app.kyc.store', ['app' => $app->aid, 'group' => $for]) }}" method="POST">
        @csrf

        <h2>About business owner</h2>
        <input class="long" type="text" name="name" placeholder="Full name">
        <input class="long" type="text" name="name" placeholder="National ID, Passport number">
        <div class="cols column">
            <input class="mr-1" type="text" name="number" placeholder="Phone number">
            <input type="text" name="email" placeholder="Email">
        </div>

        <h2>About the business</h2>
        <input class="long" type="text" name="business_name" placeholder="Business name">
        <select class="long" name="business_type">
            <option value="" selected disabled>Business type</option>
            <option value="ltd">LTD</option>
        </select>
        <textarea class="long" name="business_description" rows="10" placeholder="Description of your business"></textarea>

        <h2>KYC contracting and file management</h2>
        <p class="bold">Download Contracting Requirements</p>
        <p>Fill and sign these files and then upload them again in the field below</p>
        <a href="{{ $pdf }}" class="button" download>@svg("download") Download application forms</a>

        <p class="bold mt-3">Upload Signed Contracting Requirements</p>
        <p>Upload all documents as a pdf file only</p>
        <label class="file-upload button" for="signed-contracting-requirements">
            @svg("upload") Upload Signed Application Forms
            <input type="file" name="signed_contracting_requirements" id="signed-contracting-requirements" accept=".pdf">
        </label>

        <label for="accept" class="accept mt-3">
            <input type="checkbox" name="accept" id="accept" required>
            I/We certify that the information and supporting documentation provided in this application is true and accurate, and that I/We commit to delivering the physical KYC documents to MTN {{ $app->country->name }} within 7 days after submission. Failure to comply will lead to the service being suspended. I/We also accept to be to bound by the MTN Open API <a href="/terms-and-conditions">Terms and Conditions</a>.
        </label>

        <button class="button dark mt-3">Submit @svg("arrow-forward", "#FFFFFF")</button>
    </form>
    </div>
@endsection
