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

<form action="" method="POST" class="content">
    @csrf

    <h2>About business owner</h2>
    <input class="long" type="text" name="name" placeholder="Full name">
    <input class="long" type="text" name="name" placeholder="National ID, Passport number">
    <div class="cols">
        <input class="col-6" type="text" name="number" placeholder="Phone number">
        <input class="col-6" type="text" name="email" placeholder="Email">
    </div>
</form>
@endsection
