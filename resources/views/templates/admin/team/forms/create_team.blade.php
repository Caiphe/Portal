{{--<form  method="POST" action="{{ route('admin.team.store') }}" enctype="multipart/form-data"
      novalidate>
    @csrf

    <label class="editor-field-label">
        <label for="name">Team name *</label>
        <input type="text" name="name" value="{{ old('name') }}" id="team-name" class="form-field"
               placeholder="Enter team name" maxlength="100" autofocus>
    </label>

    <label class="editor-field-label">
        <label for="url">Team URL *</label>
        <input type="text" name="url" value="{{ old('url') }}" id="team-url"
               placeholder="Enter team URL (Eg. https://url.com)" maxlength="100">
    </label>

    <label class="editor-field-label">
        <label for="contact">Contact number *</label>
        <input type="text" name="contact" value="{{ old('contact') }}" id="team-contact"
               placeholder="Team contact number (e.g +243740000000)" maxlength="16">
    </label>

    <label class="editor-field-label">
        <label for="country">Country *</label>
        <div class="country-block-container">
            <select id="team-country" name="country" value="{{ old('country') }}" autocomplete="off">
                <option value="">Select country</option>
                @foreach($countries as $code => $name)
                    <option value="{{ $code }}" @if(old('country') === $code) selected @endif>{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </label>

    <label class="editor-field-label">
        <label for="lfile-input">Logo</label>
        <input type="file" name="logo_file" class="logo-file" id="logo-file" placeholder="Upload team logo"
                   maxlength="100" accept="image/*">
        <button type="button" class="logo-add-icon">@svg('plus', '#fff')</button>

        <small class="mb-3">*Max 5MB file size and Max Width of 2000 and Max Height of 2000.</small>


    <label class="editor-field-label">
        <label for="invitations">Team members</label>
        <input type="email" class="invitation-field" name="invitations" id="invitations"
               placeholder="Add one email at a time." maxlength="100" autocomplete="off">
        <button class="invite-btn" type="button">INVITE</button>
        <span class="error-email">Valid email required !</span>
        <div class="invite-tags" id="invite-list"></div>
    </label>

    <label class="editor-field-label">
        <label for="description">Team description</label>
        <textarea name="description" id="description" placeholder="Write a short description about your team"
                  maxlength="512">{{ old('description') }}</textarea>
    </label>


        <button class="button" >
            CREATE TEAM
        </button>

    </label>
</form>--}}


<div class="form-container">

    <h2>Details</h2>

    {{-- <form id="form-create-team" method="POST" action="{{ route('admin.team.store') }}" enctype="multipart/form-data"
            novalidate>
         @csrf
         <div class="form-group">
             <label for="team-name">Team name*</label>
             <input type="text" id="team-name" name="name" placeholder="Enter a name for your team" required>
         </div>
         <div class="form-group">
             <label for="team-url">Team URL</label>
             <input type="url" id="team-url" name="url" placeholder="Enter team URL (e.g. https://url.com)">
         </div>
         <div class="form-group">
             <label for="contact-number">Contact number</label>
             <input type="tel" id="contact-number" name="contact" placeholder="Enter your team contact number (e.g. +24374000000)">
         </div>
         <div class="form-group">
             <label for="country">Country</label>
             <select id="country" name="country">
                 <option selected value="">Select a country</option>
                 @foreach($countries as $code => $country)
                     <option value="{{ $code }}">{{ $country }}</option>
                 @endforeach
                 <!-- Add country options here -->
             </select>
         </div>
         <div class="form-group">
             <label for="logo">Logo</label>
             <input type="file" id="logo" name="logo">
             <p class="form-info">*Max 5MB file size and Max Width of 2000 and Max Height of 2000.</p>
         </div>
         <div class="form-group">
             <label for="team-members">Team members</label>
             <input type="email" id="invitations" class="invitation-field" name="team_members" placeholder="Add one or more email address and click invite">
             <div class="invite-tags" id="invite-list"></div>
             <button type="button" class="invite-button invite-btn">Invite</button>
             <span class="error-email">Valid email required !</span>

         </div>
         <div class="form-group">
             <label for="team-owner">Team owner</label>
             <select id="team-owner" name="team_owner" disabled>
                 <option value="">Please invite members to the team before selecting an owner</option>
             </select>
         </div>
         <div class="form-group">
             <label for="description">Description</label>
             <textarea id="description" name="description" placeholder="Write a short description about your team"></textarea>
         </div>
         <div class="form-group">
             <button type="submit" class="button">Complete</button>
         </div>
     </form>
 --}}
    <form id="form-create-team" method="POST" action="{{ route('teams.store') }}" enctype="multipart/form-data"
          novalidate>
        @csrf

        <div class="group">
            <label for="name">Name your team *</label>
            <input type="text" name="name" value="{{ old('name') }}" id="team-name" class="form-field"
                   placeholder="Enter team name" maxlength="100" autofocus>
        </div>

        <div class="group">
            <label for="url">Enter team URL *</label>
            <input type="text" name="url" value="{{ old('url') }}" id="team-url"
                   placeholder="Enter team URL (Eg. https://url.com)" maxlength="100">
        </div>

        <div class="group">
            <label for="contact">Enter team contact number *</label>
            <input type="text" name="contact" value="{{ old('contact') }}" id="team-contact"
                   placeholder="Team contact number (e.g +243740000000)" maxlength="16">
        </div>

        <div class="group countries">
            <label for="country">Which country are you based in? *</label>
            <div class="country-block-container">
                <select id="team-country" name="country" value="{{ old('country') }}" autocomplete="off">
                    <option value="">Select country</option>
                    @foreach($countries as $code => $name)
                        <option value="{{ $code }}" @if(old('country') === $code) selected @endif>{{ $name }}</option>
                    @endforeach
                </select>

            </div>
        </div>
        <h3>Logo</h3>

        <div class="upload-container">
            <input type="file" id="file-upload" name="logo_file" class="logo-file" accept="image/*" hidden>
            <label for="file-upload" class="upload-label">
                <div class="upload-text">Browse</div>
            </label>
        </div>
        <small class="mb-3">*Max 5MB file size and Max Width of 2000 and Max Height of 2000.</small>

        <div>
        <h3>Team members</h3>
        <div class="group">
            <label for="invitations">Add members to the team</label>
            <input type="email" class="invitation-field" name="invitations" id="invitations"
                   placeholder="Add one email at a time." maxlength="100" autocomplete="off">

            <span class="error-email">Valid email required !</span>
            <div class="invite-tags" id="invite-list"></div>
        </div>
        <button class="invite-btn" type="button">INVITE</button>
        </div>

        <div class="group">
            <label for="name">Team owner *</label>
            <input type="text" name="name" value="{{ old('name') }}" id="team-name" class="form-field"
                   placeholder="Enter team name" maxlength="100" autofocus>
        </div>
        <div class="group">
            <label for="description">Team description</label>
            <textarea name="description" id="description" placeholder="Write a short description about your team"
                      maxlength="512">{{ old('description') }}</textarea>
        </div>

        <div class="group">
            <button class="submit-button" id="create">
                Complete
            </button>
        </div>
    </form>

</div>
