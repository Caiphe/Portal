@csrf

<div class="half">
    <div class="editor-field">

    <h2>User details</h2>

    <label class="editor-field-label half">
        <h3>First name</h3>
        <input type="text" name="first_name" placeholder="First name"
               value="{{ $user->first_name ?? old('first_name') }}" autocomplete="off" maxlength="140">
    </label>

    <label class="editor-field-label half">
        <h3>Last name</h3>
        <input type="text" name="last_name" placeholder="Last name" value="{{ $user->last_name ?? old('last_name') }}"
               autocomplete="off" maxlength="140">
    </label>

    <label class="editor-field-label">
        <h3>Email address</h3>
        <input type="text" name="email" id="email" placeholder="Email"
               @if (Route::currentRouteName() == 'admin.user.edit') readonly
               @endif value="{{ $user->email ?? old('email') }}" autocomplete="off" maxlength="140">
    </label>

    @if(isset($user))
        <div class="editor-field-label half">
            <h3>Member since</h3>
            <div class="editor-field-copy">{{ $user->created_at->diffForHumans() }}</div>
        </div>
    @endif

    <label @class([
        'editor-field-label',
        'half' => isset($user)
    ])>
        <h3>Countries of operation</h3>
        <x-multiselect id="country" name="country" label="Select country"
                       :options="$countries->pluck('name', 'code')->toArray()" :selected="$userCountryCodes ?? []"/>
    </label>

    <label class="editor-field-label half">
        <h3>Password</h3>
        <input id="password" type="password" name="password" placeholder="Password" autocomplete="new-password">
        <button type="button" class="show-password reset" onclick="togglePasswordVisibility(this)"></button>
    </label>

    <label class="editor-field-label half">
        <h3>Confirm password</h3>
        <input id="password-confirm" type="password" name="password_confirmation" placeholder="Confirm password"
               autocomplete="new-password">
        <button type="button" class="show-password reset" onclick="togglePasswordVisibility(this)"></button>
    </label>

    <label class="editor-field-label password-strength">
        <div id="password-strength"></div>
        <div id="password-still-needs" role="alert"></div>
    </label>

    <label @class([
        'editor-field-label'
    ])>
        <h3>Assigned private products</h3>
        <x-multiselect id="private_products" name="private_products" label="Select private product"
                       :options="$privateProducts" :selected="old('private_products') ?: $userAssignedProducts ?? []"/>
    </label>


    <button class="button outline blue save-button">Apply changes</button>
    </div>
    {{--User status--}}
    <div class="editor-field">
        <h2>User Status</h2>
    
        <div class="user-status-block">
            <label class="editor-field-label">
                <select name="action" id="action">
                    <option value="active" {{ $user->user_status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $user->user_status !== 'active' ? 'selected' : '' }}>Inactive</option>
                </select>
            </label>
            <button type="button" id="user-status-btn"
                    class="button outline blue save-button"> Change Status
            </button>
        </div>
    </div>
</div>


<div class="half">
    @if($isAdminUser)
        <div class="editor-field ">
            <h2>Groups and roles</h2>

            <label class="editor-field-label">
                <h3><b>Groups</b> this user is responsible for</h3>
                <x-multiselect id="responsible_groups" name="responsible_groups" label="Select groups"
                               :options="$groups" :selected="$userResponsibleGroups ?? []"/>
            </label>

            <label class="editor-field-label">
                <h3><b>Role</b> this user is responsible for</h3>
                <x-multiselect id="roles" name="roles" label="Select role"
                               :options="$roles->pluck('label', 'id')->toArray()" :selected="$userRoleIds ?? []"/>
            </label>

            <label class="editor-field-label">
                <h3><b>Countries</b> this user is responsible for</h3>
                <x-multiselect id="responsible_countries" name="responsible_countries" label="Select country"
                               :options="$countries->pluck('name', 'code')->toArray()"
                               :selected="$userResponsibleCountries ?? []"/>
            </label>

            <button class="button outline blue save-button">Apply changes</button>
        </div>
    @endif

    @if(isset($user))
        <div class="editor-field">
            <h2>Authentication method</h2>
            <div class="auth-method-block">
                <div class="auth-block">
                    <span>Two factor authentication</span>
                    <span class="status-dot {{ $user->twoFactorStatus() }}">{{ $user->twoFactorStatus() }}</span>
                </div>
                <button type="button" id="reset-2fa-btn"
                        class="button outline  reset-2fa @if($user_twofa_reset_request) blue @endif">Reset 2FA
                </button>
            </div>
        </div>
    @endif

    {{-- This is available only to super admin --}}
    @if($deletionRequest && in_array('Admin', $adminRoles))
    <div class="editor-field">
        <h2>User Deletion</h2>
        <div class="main-delete-container">
            <div class="users-deletion-data">
                <div class="main-data-section">
                    <div class="bold-text">Please exercise caution when deleting users. </div>
                    <p>Related user data such as teams and applications will be affected.</p>
                </div>
        
                <div class="button-block">
                    <button class="button red confirm-user-deletion" id="confirm-user-deletion" type="button">Delete user</button>
                </div>
            </div>
            <div class="delete-warning">
                @svg('full-warning') <span>This user has a pending delete request.</span>
            </div>
        </div>
    </div>
    @endif

    @if(!$deletionRequest)
    <div class="editor-field">
        <h2>User Deletion</h2>
        <div class="main-delete-container">
            <div class="users-deletion-data">
                <div class="main-data-section">
                    <div class="bold-text">Only super admins can delete users from the portal.</div>
                    <p>Please request a deletion of this user if you would like delete this user.</p>
                </div>
                <div class="button-block">
                    <button class="button red" id="request-user-deletion" type="button">Request user deletion</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>


