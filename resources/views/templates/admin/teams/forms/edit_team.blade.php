
<div class="form-container">
    <h2>Details</h2>
    <form id="create-team" method="POST" action="{{ route('admin.team.store') }}" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="form-group">
            <label for="name">Name your team *</label>
            <input type="text" name="name" value="{{ old('name') }}" id="team-name" class="form-field" placeholder="Enter team name" maxlength="100" autofocus>
        </div>

        <div class="form-group">
            <label for="url">Enter team URL *</label>
            <input type="text" name="url" value="{{ old('url') }}" id="team-url" placeholder="Enter team URL (Eg. https://url.com)" maxlength="100">
        </div>

        <div class="form-group">
            <label for="contact">Enter team contact number *</label>
            <input type="text" name="contact" value="{{ old('contact') }}" id="team-contact" placeholder="Team contact number (e.g +243740000000)" maxlength="16">
        </div>

        <div class="form-group">
            <label for="country">Which country are you based in? *</label>
            <div class="country-block-container">
                <select id="team-country" name="country" autocomplete="off">
                    <option value="">Select country</option>
                    @foreach($countries as $code => $name)
                        <option value="{{ $code }}" @if(old('country') === $code) selected @endif>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="upload-container">
            <h3 class="file-heading">Logo</h3>

            <div class="file-upload-block">
                <div class="file-previews" id="file-previews" style="display: none;">
                    <img src="" alt="Preview" id="image-preview" class="image-placeholder">
                </div>
                <input type="file" id="file-upload" name="logo_file" class="logo-file" accept="image/*" hidden>
                <label for="file-upload" class="upload-label">Browse</label>
            </div>

            <small class="small-gray">*Max 5MB file size and Max Width of 2000 and Max Height of 2000.</small>
        </div>

        <div id="email-input-container">
            <input type="text" id="email-input" placeholder="Enter emails" />
        </div>
        <div id="tags-container"></div>
        <button type="button" id="invite-button">INVITE</button>
        <input type="hidden" name="emails" id="email-list">

        <div class="form-group">
            <label for="team_owner">Team owner *</label>
            <input type="text" name="team_owner" value="{{ old('team_owner') }}" id="team-owner" class="form-field" placeholder="Enter team owner" maxlength="100" autofocus>
        </div>
        <div class="form-group">
            <label for="description">Team description</label>
            <textarea name="description" id="description" placeholder="Write a short description about your team" maxlength="512">{{ old('description') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="submit-button" id="create">
                Complete
            </button>
        </div>
    </form>
</div>

