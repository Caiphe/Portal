@extends('layouts.sidebar')

@push('styles')
<link rel="stylesheet" href="/css/templates/user/show.css">
@endpush

@section('title')
Update profile
@endsection

@section('sidebar')
    <x-sidebar-accordion id="sidebar-accordion" :active="'/' . request()->path()"
        :list="
        [
            'MANAGE' => 
            [
                [ 'label' => 'Profile', 'link' => '/profile'],
                [ 'label' => 'Approved apps', 'link' => '/apps'],
                [ 'label' => 'Revoked apps', 'link' => '/apps']
            ],
            'DISCOVER' =>
            [
                [ 'label' => 'Browse all our products', 'link' => '/products'],
                [ 'label' => 'Working with our products', 'link' => '/getting-started']
            ]
        ]"
    />   
@endsection

@section("content")
    <x-heading heading="Profile"/>
    <div id="profile">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <label id="profile-picture-label" for="profile-picture" style="background-image: url({{$user['profile_picture']}});">
            <input type="file" name="profile-picture" id="profile-picture" accept="image/*">
        </label>
        <form action="{{route('user.profile.update', [$user['id']])}}" id="profile-form" method="POST">
            @csrf
            @method('put')
            <h2>Personal details</h2>
            <input type="text" name="first_name" value="{{$user['first_name']}}" placeholder="First name" autocomplete="first_name">
            <input type="text" name="second_name" value="{{$user['last_name']}}" placeholder="Second name" autocomplete="last_name">
            <input type="email" name="email" value="{{$user['email']}}" placeholder="Email" autocomplete="email">
            <h2>Password</h2>
            <label class="password-label" for="password">
                <input type="password" name="password" id="password" placeholder="Password" autocomplete="new-password">
                <button type="button" class="fab show-password" onclick="togglePasswordVisibility(this)"></button>
            </label>
            <label class="password-label" for="passwordConfirmation">
                <input type="password" name="password_confirmation" id="passwordConfirmation" placeholder="Confirm password" autocomplete="new-password">
                <button type="button" class="fab show-password" onclick="togglePasswordVisibility(this)"></button>
            </label>
            <h2>Your selected countries</h2>
            <div class="locations">
                @foreach($locations as $location)
                <label for="{{$location}}">
                    <input type="checkbox" name="locations[]" value="{{$location}}" id="{{$location}}" autocomplete="off" @if(in_array($location, $userLocations)) checked @endif>
                    <span title="{{$location}}">@svg($location, '#000000', 'images/locations')</span>
                </label>
                @endforeach
            </div>
            <button class="dark">Save</button>
        </form>
    </div>
@endsection

@push('scripts')
<script src="/js/templates/user/show.js"></script>
@endpush