<form  method="POST" action="{{ route('admin.team.store') }}" enctype="multipart/form-data"
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
</form>

