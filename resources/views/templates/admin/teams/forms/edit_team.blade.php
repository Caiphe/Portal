<div class="form-container editor-field">
    <h2>Details</h2>
    <form id="create-team" method="POST"  action="{{ route('admin.team.update', $team->id) }}"  enctype="multipart/form-data" novalidate>
        @csrf

        <label class="editor-field-label">
            <h3 for="name">Team name *</h3>
            <input type="text" name="name" value="{{ old('name', $team->name) }}" id="team-name" class="form-field" placeholder="Enter a name for your team" maxlength="100" autofocus>
            @error('name')
            <div class="error">{{ $message }}</div>
            @enderror
        </label>

        <label class="editor-field-label">
            <h3 for="url">Team URL*</h3>
            <input type="text" name="url" value="{{ old('url', $team->url) }}" id="team-url" placeholder="Enter team URL (Eg. https://url.com)" maxlength="100">
            @error('url')
            <div class="error">{{ $message }}</div>
            @enderror
        </label>

        <label class="editor-field-label">
            <h3 for="contact">Contact number *</h3>
            <input type="text" name="contact" value="{{ old('contact', $team->contact) }}" id="team-contact" placeholder="Team contact number (e.g +243740000000)" maxlength="16">
            @error('contact')
            <div class="error">{{ $message }}</div>
            @enderror
        </label>

        <label class="editor-field-label">
            <h3 for="country">Country*</h3>
            <div class="country-block-container">
                <select id="team-country" name="country" autocomplete="off">
                    <option value="">Select country</option>
                    @foreach($countries as $code => $name)
                        <option value="{{ $code }}" @if(old('country', $team->country) === $code) selected @endif>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            @error('country')
            <div class="error">{{ $message }}</div>
            @enderror
        </label>

        <div class="upload-container">
            <h3 class="file-heading">Logo</h3>
            <div class="file-upload-block">
                @if($team->logo)
                    <div class="file-previews" id="file-previews">
                        <img id="image-preview" class="image-placeholder" src="{{ asset($team->logo) }}" alt="Image Preview">
                    </div>
                @endif
                <input type="file" id="file-upload" name="logo_file" class="logo-file" accept="image/*" hidden>
                <label for="file-upload" class="upload-label">Browse</label>
            </div>
            <small class="small-gray">*Max 5MB file size and Max Width of 2000 and Max Height of 2000.</small>
            @error('logo_file')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <label class="editor-field-label" style="display: none;">
            <h3 for="team_owner">Team owner *</h3>
            <select name="team_owner" id="team-owner" class="form-field" autofocus>
                <option value="{{ $team->owner->id }}" selected>{{ $team->owner->email }}</option>
            </select>
        </label>

        <label class="editor-field-label">
            <h3 for="description">Team description</h3>
            <textarea name="description" id="description" placeholder="Write a short description about your team" style="height: 90px">{{ old('description', $team->description) }}</textarea>
            @error('description')
            <div class="error">{{ $message }}</div>
            @enderror
        </label>

        <button type="submit" class="submit-button" id="pdate">
            Save changes
        </button>
    </form>
</div>
