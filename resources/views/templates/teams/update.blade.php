@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/teams/create.css') }}">
@endpush

@extends('layouts.sidebar')

@section('sidebar')

    <x-sidebar-accordion id="sidebar-accordion" active="/teams" :list="
    [ 'Manage' =>
        [
            [ 'label' => 'Profile', 'link' => '/profile'],
            [ 'label' => 'My apps', 'link' => '/apps'],
            [ 'label' => 'My Teams', 'link' => '/teams']
        ],
        'Discover' =>
        [
            [ 'label' => 'Browse all products', 'link' => '/products'],
            [ 'label' => 'Working with our products','link' => '/getting-started'],
        ]
    ]
    " />
@endsection

@section('title')
    Update Team
@endsection

@section('content')

    <x-heading heading="My team" tags="Dashboard"></x-heading>

    <x-twofa-warning class="tall"></x-twofa-warning>

    <div class="content">

        <div class="content-header mt-40">
            <h2>Team Profile</h2>
        </div>

        <form id="form-create-team" method="POST" action="{{ route('teams.update', $team->id) }}" enctype="multipart/form-data">
            @method('put')
            @csrf

            <div class="group">
                <label for="name">Enter team Name </label>
                <input type="text" name="name" id="name" placeholder="Enter team name" maxlength="100" value="{{ $team->name }}" required>
            </div>

            <div class="group">
                <label for="url">Enter team URL</label>
                <input type="text" name="url" id="url" placeholder="Enter team URL" maxlength="100" value="{{ $team->url }}"  required>
            </div>

            <div class="group">
                <label for="contact">Enter team contact number</label>
                <input type="text" name="contact" id="contact" placeholder="Enter team contact number" maxlength="100" value="{{ $team->contact }}"  required>
            </div>

            <div class="group countries">
                <label for="country">Which country are you based in?</label>
                <div class="country-block-container">
                    <select id="country" name="country">
                        <option value="">Select country</option>
                        @foreach($countries as $code => $name)
                            <option value="{{ $code }}" @if($code === $team->country) selected @endif>{{ $name }}</option>
                        @endforeach
                    </select>
                    @svg('chevron-down', '#000')
                </div>
            </div>

            <div class="group">
                <label for="lfile-input">Upload team logo</label>
                <label for="file-input" class="logo-file-container">
                    <span class="upload-file-name">Upload team logo</span>
                    <input type="file" name="logo_file" class="logo-file" id="logo-file" placeholder="Upload team logo" maxlength="100"  {{ $team->logo }} accept="image/*">
                    <button type="button" class="logo-add-icon">@svg('plus', '#fff')</button>
                </label>
            </div>

            <div class="group team-logo-container" style="background-image: url({{ $team->logo }})"></div>

            {{-- <div class="group custom-manage-team">
               <a href="{{ route('team.show', $team->id) }}">Manage team members</a>
            </div> --}}

            <div class="group">
                <label for="description">Company description</label>
                <textarea name="description" id="description" placeholder="Write a short description about your team" >{{ $team->description }}</textarea>
            </div>

            <div class="form-actions">
                <button class="dark next " id="create">
                    SAVE & SUBMIT @svg('arrow-forward', '#ffffff')
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('/js/templates/teams/update.js') }}"></script>
@endpush
