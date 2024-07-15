<div class="form-container editor-field">
    <h2>Details</h2>
    <form id="create-team" method="POST" action="{{ route('admin.team.store') }}" enctype="multipart/form-data" novalidate>
        @csrf
        <label class="editor-field-label">
            <h3 for="name">Team name *</h3>
            <input type="text" name="name" value="{{ old('name') }}" id="team-name" class="form-field" placeholder="Enter a name for your team" maxlength="100" autofocus>
        </label>

        <label class="editor-field-label">
            <h3 for="url">Enter team URL *</h3>
            <input type="text" name="url" value="{{ old('url') }}" id="team-url" placeholder="Enter team URL (Eg. https://url.com)" maxlength="100">
        </label>

        <label class="editor-field-label">
            <h3 for="contact">Enter team contact number *</h3>
            <input type="text" name="contact" value="{{ old('contact') }}" id="team-contact" placeholder="Team contact number (e.g +243740000000)" maxlength="16">
        </label>

        <label class="editor-field-label">
            <h3 for="country">Which country are you based in? *</h3>
            <div class="country-block-container">
                <select id="team-country" name="country" autocomplete="off">
                    <option value="">Select country</option>
                    @foreach($countries as $code => $name)
                        <option value="{{ $code }}" @if(old('country') === $code) selected @endif>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </label>

        <div class="upload-container">
            <h3 class="file-heading">Logo</h3>
            <div class="file-upload-block">
                <div class="file-previews" id="file-previews" style="display: none;">
                    <img id="image-preview" class="image-placeholder" src="" alt="Image Preview">
                </div>
                <input type="file" id="file-upload" name="logo_file" class="logo-file" accept="image/*" hidden>
                <label for="file-upload" class="upload-label">Browse</label>
            </div>
            <small class="small-gray">*Max 5MB file size and Max Width of 2000 and Max Height of 2000.</small>
        </div>


        <label class="editor-field-label team-member-block">
            <h3 class="team-member-heading">Team Members</h3>
            <p>Add members to the team *</p>

            <div id="email-input-container">
                <input type="text" id="email-input" placeholder="Enter emails" />
            </div>

            <div id="tags-container"></div>
            <button type="button" id="invite-button">INVITE</button>
            <input type="hidden" name="emails" id="email-list">
        </label>

        <label class="editor-field-label">
            <h3 for="team_owner">Team owner *</h3>
            <select name="team_owner" id="team-owner" class="form-field" autofocus>
                <option value=""  readonly="">Please invite members to the team before selecting an owner</option>
            </select>
        </label>

        <label class="editor-field-label">
            <h3 for="description">Description</h3>
            <textarea name="description" id="description" placeholder="Write a short description about your team" maxlength="512">{{ old('description') }}</textarea>
        </label>

        <button type="submit" class="submit-button" id="create">
            Complete
        </button>
    </form>
</div>
