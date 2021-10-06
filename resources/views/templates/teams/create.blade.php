@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/teams/create.css') }}">
@endpush

@extends('layouts.sidebar')

@section('sidebar')

    <x-sidebar-accordion id="sidebar-accordion" active="/teams/create" :list="
    [ 'Manage' =>
        [
            [ 'label' => 'Profile', 'link' => '/profile'],
            [ 'label' => 'My apps', 'link' => '/apps'],
            [ 'label' => 'Teams', 'link' => '/teams/create']
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
    Create Team
@endsection

@section('content')

    <x-heading heading="My teams" tags="Dashboard"></x-heading>

    <x-twofa-warning class="tall"></x-twofa-warning>

    <div class="content">

        <div class="content-header mt-40">
            @if($userOwnsTeam === false)
                <h2>It looks like you don't have any teams yet!</h2>
            @elseif($hasTeams)
                <h2>Create a New Team!</h2>
            @endif
            <p>Fortunately, it's very easy to create one. Let's begin by filling out your teams details.</p>
        </div>

        <form id="form-create-team" novalidate>

            <div class="group">
                <label for="name">Name your team</label>
                <input type="text" name="name" id="name" placeholder="Enter team name" maxlength="100" required>
            </div>

            <div class="group">
                <label for="url">Enter team URL</label>
                <input type="text" name="url" id="url" placeholder="Enter team URL" maxlength="100" required>
            </div>

            <div class="group">
                <label for="contact">Enter team contact number</label>
                <input type="text" name="contact" id="contact" placeholder="Enter team contact number" maxlength="100" required>
            </div>

            <div class="group countries">
                <label for="country">Which country are you based in?</label>
                <div class="country-block-container">
                    <select id="country" name="country">
                        <option value="">Select country</option>
                        @foreach($countries as $code => $name)
                            <option value="{{ $code }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    @svg('chevron-down', '#000')
                </div>

            </div>

            <div class="group">

                <label for="lfile-input">Upload team logo</label>
                <label for="file-input" class="logo-file-container">
                    <span class="upload-file-name">Upload team logo</span>
                    <input type="file" name="file-input" class="logo-file" id="logo-file" placeholder="Upload team logo" maxlength="100" required>
                    <button type="button" class="logo-add-icon">@svg('plus', '#fff')</button>
                </label>
            </div>

            <!---
                This control could act like a an input field with email tags that could
                the be converted into an array and posted back to the back-end
            -->
            <div class="group">
                <label for="invitations">Invite colleagues or other users</label>
                <input type="text" name="invitations" id="invitations" placeholder="Add email to invite other users" maxlength="100" required>
                <button class="invite-btn">INVITE</button>

                <div class="invite-tags">
                    <span class="each-tag">marc@plusnarrative.com</span>
                    <span class="each-tag">max@plusnarrative.com</span>
                </div>
            </div>

            <div class="group">
                <label for="description">Company description</label>
                <textarea name="description" id="description" placeholder="Write a short description about your team" ></textarea>
            </div>

            <div class="form-actions">
                <button class="dark next " id="create">
                    CREATE TEAM @svg('arrow-forward', '#ffffff')
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        var logoFile = document.querySelector('.logo-file');
        var fileName = document.querySelector('.upload-file-name');
        var fileBtn = document.querySelector('.logo-add-icon');

        logoFile.addEventListener('change', function(){
            var newFile = this.files[0].name.split('.');
            var extension = newFile[1];
            var filename = ''
            if(newFile[0].length > 20){filename = newFile[0].substr(0, 20) + '...' + extension;}
            else{filename = newFile[0] + '.'+ extension;}
            fileName.innerHTML = filename
            fileBtn.classList.add('active');
        });

        var createButton = document.getElementById('create');
        var submit = document.getElementById('form-create-team').addEventListener('submit', handleCreate);

        function handleCreate()
        {
            var elements = this.elements;
            var newTeam = {
                name: elements['name'].value,
                url: elements['url'].value,
                contact: elements['contact'].value,
                country: [elements['country'].selectedIndex].value,
                logo: elements['logo'].value,
                invitations: {},
                description: elements['description'].value,
                is_company: elements['is_company_team'].value,
            };

            // Handle invitees list

            // Handle file upload

            // Other validations will rely on back-end

            e.preventDefault();
            createButton.disabled = true;
            createButton.textContent = 'Creating...';

            var url = "{{ route('teams.store') }}";
            var xhr = new XMLHttpRequest();

            xhr.open('POST', url);
            xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.send(JSON.stringify(newTeam));

            xhr.onload = function() {
                if (xhr.status === 200) {
                    addAlert('success', ['Team created successfully', 'You will be redirected to your teams page shortly.'], function(){
                        window.location.href = "{{ route('teams.listing') }}";
                    });
                } else {
                    var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

                    if(result.errors) {
                        result.message = [];
                        for(var error in result.errors){
                            result.message.push(result.errors[error]);
                        }
                    }

                    addAlert('error', result.message || 'Sorry there was a problem creating your team. Please try again.');

                    createButton.removeAttribute('disabled');
                    createButton.textContent = 'Create';
                }
            };
        }

    </script>
@endpush
