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
<style>

   #main .form-container > h2 {
        padding: 24px;
        flex-wrap: wrap;
        align-self: flex-start;
        background-color: #FFF;
        border-radius: 8px;
        width: 100%;
       margin: 0;
       border-radius: 8px;
       box-shadow: 0px 8px 8px rgba(0, 0, 0, 0.04);
       font-size: 20px;
       font-family: "mtn-regular", sans-serif;
       font-weight: normal;
       flex: 1 1 100%;

    }
    .form-container h2 {
        margin-top: 0;
        font-size: 24px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .form-group input[type="text"],
    .form-group input[type="url"],
    .form-group input[type="tel"],
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    .form-group input[type="file"] {
        padding: 10px 0;
    }
    .form-group .form-info {
        font-size: 12px;
        color: #666;
    }
    .form-group button {
        padding: 10px 20px;
        border: none;
        background-color: #ffcc00;
        color: white;
        font-size: 16px;
        border-radius: 4px;
        cursor: pointer;
    }
    .form-group button:hover {
        background-color: #e6b800;
    }
    .form-group .invite-button {
        margin-top: 10px;
    }
</style>

<div class="form-container">

    <h2>Details</h2>

    <form  method="POST" action="{{ route('admin.team.store') }}" enctype="multipart/form-data"
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
                @foreach($countries as $country)
                    <option value="{{ $country }}">{{ $country }}</option>
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
            <input type="text" id="team-members" name="team_members[]" placeholder="Add one or more email address and click invite">
            <button type="button" class="invite-button">Invite</button>
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
            <button type="submit">Complete</button>
        </div>
    </form>

</div>
