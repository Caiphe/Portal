@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/teams/show.css') }}">
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
Teams
@endsection

@section('content')
<x-heading heading="Teams" tags="Dashboard">
    <a href="/" class="button dark outline">Edit profile</a>
</x-heading>

{{-- Edit teammate --}}
<div class="modal-container">
    <div class="overlay-container"></div>
    <div class="add-teammate-block">
        <button class="close-modal">@svg('close-popup', '#000')</button>
        <h2 class="team-head">Add teammate</h2>
        <p class="teammate-text">Invite additional team members or other users</p>
        <form class="form-teammate">
            <div class="form-group-container">
                <input type="text" class="form-control teammate-email" placeholder="Add email to invite users" />
                <button type="submit" class="invite-btn">INVITE</button>
            </div>
            <div class="radio-container">
                <x-radio-round id="user-radio" name="role_name" value="Administrator">Administrator</x-radio-round>
                <x-radio-round id="user-radio" name="role_name" value="user">User</x-radio-round>
            </div>
        </form>
    </div>
</div>
{{-- Edit team mate ends --}}

{{-- Make Admin Modal Container --}}
<div class="make-admin-modal-container">
    <div class="admin-overlay-container"></div>
    <div class="add-teammate-block">
        <button class="admin-close-modal">@svg('close-popup', '#000')</button>
        <h2 class="team-head">Make Admin</h2>
        <p class="teammate-text">Would you like change this user's level of access to <strong>administrator</strong> ?</p>
        <p class="admin-user-name">Xoliswa Shandu</p>
        <form class="form-delete-user">
            <button type="button" class="btn primary mr-10 make-admin-cancel-btn">CANCEL</button>
            <button type="button" class="btn dark">REMOVE</button>
        </form>
    </div>
</div>
{{-- Make admin ends --}}

{{-- Make user modal Container --}}
<div class="make-user-modal-container">
    <div class="user-overlay-container"></div>
    <div class="add-teammate-block">
        <button class="user-close-modal">@svg('close-popup', '#000')</button>
        <h2 class="team-head">Make User</h2>
        <p class="teammate-text">Would you like change this user's level of access to <strong>user</strong> ?</p>
        <p class="admin-user-name">Xoliswa Shandu</p>
        <form class="form-delete-user">
            <button type="button" class="btn primary mr-10 user-admin-cancel-btn">CANCEL</button>
            <button type="button" class="btn dark">REMOVE</button>
        </form>
    </div>
</div>
{{-- Make user modal ends --}}

{{-- Delete User Modal --}}
<div class="delete-modal-container">
    <div class="delete-overlay-container"></div>

    <div class="delete-user-block">
        <button class="delete-close-modal">@svg('close-popup', '#000')</button>

        <h2 class="team-head">Remove User</h2>
        <p class="teammate-text">Are you sure you want to remove this user?</p>
        <p class="user-name">Xoliswa Shandu</p>

    {{-- Form to confirm the users removal --}}
        <form class="form-delete-user">
            <button type="button" class="btn primary mr-10 cancel-remove-user-btn">CANCEL</button>
            <button type="button" class="btn dark confirm-delete-btn">REMOVE</button>
        </form>
    </div>

    {{-- This show up if you are the owner so you should assign a different owner --}}
    <div class="confirm-delete-block">
        <button class="confirm-delete-close-modal">@svg('close-popup', '#000')</button>
        <h2 class="team-head custom-head">Warning</h2>
        <p class="remove-user-text">
            <span class="user-name">Xoliswa Shandu</span> You are the owner/creator of this team profile. To be able to delete this account, please assign ownership to another user
        </p>

        <div class="scrollable-users-container">
            <ul class="list-users-container">
                <li class="each-user">
                    <div class="users-thumbnail" style="background-image: url('/images/user-thumbnail.jpg')"></div>
                    <div class="user-full-name">Xoliswa Shandu</div>
                    <div class="check-container">
                        <x-radio-round name="user-assignee" value=""></x-radio-round>
                    </div>
                </li>
                <li class="each-user">
                    <div class="users-thumbnail" style="background-image: url('/images/user-thumbnail.jpg')"></div>
                    <div class="user-full-name">Max Bombwell</div>
                    <div class="check-container">
                        <x-radio-round name="user-assignee" value=""></x-radio-round>
                    </div>
                </li>
                <li class="each-user">
                    <div class="users-thumbnail" style="background-image: url('/images/user-thumbnail.jpg')"></div>
                    <div class="user-full-name">Cassy Buary</div>
                    <div class="check-container">
                        <x-radio-round name="user-assignee" value=""></x-radio-round>
                    </div>
                </li>
                <li class="each-user">
                    <div class="users-thumbnail" style="background-image: url('/images/user-thumbnail.jpg')"></div>
                    <div class="user-full-name">Cassy Buary</div>
                    <div class="check-container">
                        <x-radio-round name="user-assignee" value=""></x-radio-round>
                    </div>
                </li>
            </ul>
        </div>

        <form class="form-delete-user">
            <button type="button" class="btn primary mr-10 cancel-removal-btn">CANCEL</button>
            <button type="button" class="btn dark">REMOVE</button>
        </form>
    </div>
</div>
{{-- Delete User Ends --}}


{{-- Transfer ownership Modal--}}
<div class="ownweship-modal-container">
    <div class="ownweship-overlay-container"></div>
    {{-- This show up if you are the owner so you should assign a different owner --}}
    <div class="transfer-ownership-block">
        <button class="ownweship-close-modal">@svg('close-popup', '#000')</button>
        <h2 class="team-head custom-head">Transfer Ownership</h2>
        <p class="remove-user-text">Which team member would you like to transfer ownership to? </p>

        <div class="scrollable-users-container">
            <ul class="list-users-container">
                <li class="each-user">
                    <div class="users-thumbnail" style="background-image: url('/images/user-thumbnail.jpg')"></div>
                    <div class="user-full-name">Xoliswa Shandu</div>
                    <div class="check-container">
                        <x-radio-round name="transfer-ownership-check" value=""></x-radio-round>
                    </div>
                </li>
                <li class="each-user">
                    <div class="users-thumbnail" style="background-image: url('/images/user-thumbnail.jpg')"></div>
                    <div class="user-full-name">Max Bombwell</div>
                    <div class="check-container">
                        <x-radio-round name="transfer-ownership-check" value=""></x-radio-round>
                    </div>
                </li>
                <li class="each-user">
                    <div class="users-thumbnail" style="background-image: url('/images/user-thumbnail.jpg')"></div>
                    <div class="user-full-name">Cassy Buary</div>
                    <div class="check-container">
                        <x-radio-round name="transfer-ownership-check" value=""></x-radio-round>
                    </div>
                </li>
                <li class="each-user">
                    <div class="users-thumbnail" style="background-image: url('/images/user-thumbnail.jpg')"></div>
                    <div class="user-full-name">Cassy Buary</div>
                    <div class="check-container">
                        <x-radio-round name="transfer-ownership-check" value=""></x-radio-round>
                    </div>
                </li>
            </ul>
        </div>

        <form class="form-delete-user mt-40">
            <button type="button" class="btn primary mr-10 ownership-removal-btn">CANCEL</button>
            <button type="button" class="btn dark transfer-btn">TRANSFER</button>
        </form>

    </div>
</div>

<div class="mt-2">
    {{-- Top ownerhip block container --}}
    <div class="top-ownership-banner">
        <div class="message-container">You have been requested to be the owner of this team.</div>
        <div class="btn-block-container">
            <a type="button" href="#transfer-ownership" class="button blue-button accept-transfer">Accept request</a>
            <a type="button" href="#transfer-ownership" class="button blue-button revoke-transfer">Revoke request</a>
        </div>
    </div>

    <div class="header-block team-name-logo">
        {{-- To replace with the company logo --}}
        <div class="team-name-logo-container">
            <div class="team-logo" style="background-image: url('/images/user-thumbnail.jpg')"></div> 
            <h2>{{  $team->name }}</h2>
        </div>

       <button class="btn dark make-owner">Select a new owner</button>

    </div>
    <h5>Team bio</h5>
    <p>{{ $team->description }}</p>
    <div class="detail-left team-detail-left">
        <div class="detail-row cols no-wrap">
            <div class="detail-item"><strong>Contact number</strong></div>
            <div class="detail-item detail-item-description">{{ $team->contact }}</div>
        </div>

        <div class="detail-row cols no-wrap">
            <div class="detail-item"><strong>Company URL</strong></div>
            <div class="detail-item detail-item-description">{{ $team->url }}</div>
        </div>

        <div class="detail-row cols no-wrap">
            <div class="detail-item"><strong>Country</strong></div>
            <div class="detail-item detail-item-description">{{ $team->country }} </div>
        </div>
    </div>
    <div class="column">
        <div class="team-members-header">
            <h2>Team membership</h2>
            <button class="outline dark add-team-mate-btn">Add a teammate</button>
        </div>
    </div>

    <div class="main-team-container">
        <div class="column team-members-list">
            <table class="team-members">
                <tr class="table-title" >
                    <td class="bold">Member name @svg('arrow-down' ,'#cdcdcd')</td>
                    <td class="bold">Role @svg('arrow-down' ,'#cdcdcd')</td>
                    <td class="bold">2FA Status @svg('arrow-down' ,'#cdcdcd')</td>
                </tr>

                @foreach($team->users as $teamUser)
                    <tr>
                        <td class="member-name-profile">
                            <div class="member-thumbnail"  style="background-image: url('/images/user-thumbnail.jpg')"></div>
                            <p>
                                <strong> {{ $teamUser->first_name }} {{ $teamUser->last_name }}</strong>
                                ({{ $teamUser->email }})
                            </p>

                            @if($teamUser->isTeamOwner())
                                <span class="owner-tag red-tag">OWNER</span>
                            @endif
                        </td>
                        <td>{{ $teamUser->roles()->first()->name  === 'admin' ? 'Administrator' : ucfirst($teamUser->roles()->first()->name) }}</td>
                        <td class="column-container">{{ $teamUser->twoFactorStatus() }}
                            <button class="btn-actions"></button>

                            {{-- user action menu --}}
                            <div class="block-actions">
                                <ul>
                                    <li><button class="make-admin">Make administrator</button></li>
                                    <li><button class="make-user">Make User</button></li>
                                    <li><button class="user-delete">Delete</button></li>
                                </ul>
                            </div>
                            {{-- Block end --}}

                            <div class="block-hide-menu"></div> 
                        </td>
                    </tr>
                @endforeach

            </table>

            <button class="outline dark add-team-mate-btn-mobile">Add a teammate</button>

        </div>
    </div>

    <div class="transfer-ownership-container" id="transfer-ownership">
            {{-- Transfer ownership container --}}
        <div class="transfer-owner-ship-heading">
            <h2>Transfer ownership</h2>
            {{-- You can add non-active to make--}}
        </div>

        {{-- Transfer request --}}
        <div class="trasfer-container">
            <h4>Transfer requests</h4>
            <div class="site-text">You have been requested to be the new owner of this team. please choose if you would like to accept or revoke the request</div>
            <div class="site-text">You are not the owner of this team, you cannot modify ownership of this team </div>

            <div class="transfer-btn-block">
                <button type="button" class="btn dark dark-accept">Accept request</button>
                <button type="button" class="btn dark dark-revoked">Revoke request</button>
            </div>
        </div>
    </div>
    
    <div class="column" id="app-index">
        <div class="row">
            <div class="approved-apps">
                <div class="heading-app">
                    @svg('chevron-down', '#000000')

                    <h3>Approved Apps</h3>
                </div>

                <div class="updated-app">
                    <div class="head headings-container">
                        <div class="column-heading">
                            <p>App Name @svg('arrow-down' ,'#cdcdcd')</p>
                        </div>

                        <div class="column-heading">
                            <p>Callback URL</p>
                        </div>

                        <div class="column-heading">
                            <p>Country @svg('arrow-down' ,'#cdcdcd')</p>
                        </div>

                        <div class="column-heading">
                            <p>Creator @svg('arrow-down' ,'#cdcdcd')</p>
                        </div>

                        <div class="column-heading">
                            <p>Date created @svg('arrow-down' ,'#cdcdcd')</p>
                        </div>
                        <div class="column-heading">
                            <p></p>
                        </div>
                    </div>

                    <div class="body app-updated-body">
                        @forelse($approvedApps as $app)
                            @if(!empty($app['attributes']))
                                <x-app-updated
                                    :app="$app"
                                    :attr="$app['attributes']"
                                    :details="$app['developer']"
                                    :details="$app['developer']"
                                    :countries="!is_null($app->country) ? [$app->country->code => $app->country->name] : ['globe' => 'globe']"
                                    :type="$type = 'approved'">
                                </x-app-updated>
                            @endif
                        @empty
                            <p>No approved apps.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>



        <div class="row" id="app">
            <div class="revoked-apps">
                <div class="heading-app">
                    @svg('chevron-down', '#000000')

                    <h3>Revoked Apps</h3>
                </div>

                <div class="updated-app">
                    <div class="head">
                        <div class="column-heading">
                            <p>App Name @svg('arrow-down' ,'#cdcdcd')</p>
                        </div>

                        <div class="column-heading">
                            <p>Callback URL</p>
                        </div>

                        <div class="column-heading">
                            <p>Country @svg('arrow-down' ,'#cdcdcd')</p>
                        </div>

                        <div class="column-heading">
                            <p>Creator @svg('arrow-down' ,'#cdcdcd')</p>
                        </div>

                        <div class="column-heading">
                            <p>Date created @svg('arrow-down' ,'#cdcdcd')</p>
                        </div>
                        <div class="column-heading">
                            <p></p>
                        </div>
                    </div>
                    <div class="body app-updated-body">
                        @forelse($revokedApps as $app)
                            @if(!empty($app['attributes']))
                            <x-app-updated
                                :app="$app"
                                :attr="$app['attributes']"
                                :details="$app['developer']"
                                :details="$app['developer']"
                                :countries="!is_null($app->country) ? [$app->country->code => $app->country->name] : ['globe' => 'globe']"
                                :type="$type = 'approved'">
                            </x-app-updated>
                            @endif
                        @empty
                            <p>No revoked apps.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    var btnActions = document.querySelectorAll('.btn-actions');
    var hideMenuBlock = document.querySelector('.block-hide-menu');
    var blockActions = document.querySelectorAll('.block-actions');
    var mainUserMenu = document.querySelector('.main-users-menu')
    var clodeModal = document.querySelector('.close-modal');
    var deleteClodeModal = document.querySelector('.delete-close-modal');
    var modalContainer = document.querySelector('.modal-container');
    var addTeammateBtn = document.querySelector('.add-team-mate-btn');
    var addTeamMobile = document.querySelector('.add-team-mate-btn-mobile');
    var overlayContainer = document.querySelector('.overlay-container');
    var deleteOverlayContainer = document.querySelector('.delete-overlay-container');
    var cancelRemoveUserBtn = document.querySelector('.cancel-remove-user-btn');

    for(var i = 0; i < btnActions.length; i++) {
        btnActions[i].addEventListener('click', showUserAction)
    }
    function showUserAction(){
        this.nextElementSibling.classList.add('show');
        hideMenuBlock.classList.add('show');
    }

    hideMenuBlock.addEventListener('click', hideActions);
    function hideActions(){
        this.classList.remove('show');
        this.previousElementSibling.classList.remove('show')
    }
    
    addTeammateBtn.addEventListener('click', hideModalContainer);
    addTeamMobile.addEventListener('click', hideModalContainer);
    function hideModalContainer(){
        modalContainer.classList.add('show');
    }

    clodeModal.addEventListener('click', showModalContainer);
    overlayContainer.addEventListener('click', showModalContainer);

    function showModalContainer(){
        modalContainer.classList.remove('show');
    }

    // Show delete modal
    var userDeleteBtn = document.querySelector('.user-delete');
    var deleteModalContainer = document.querySelector('.delete-modal-container')
    userDeleteBtn.addEventListener('click', function(){
        deleteModalContainer.classList.add('show');
    });

    deleteClodeModal.addEventListener('click',hideDeleteUserModal);
    deleteOverlayContainer.addEventListener('click', hideDeleteUserModal);
    cancelRemoveUserBtn.addEventListener('click', hideDeleteUserModal);
    document.querySelector('.cancel-removal-btn').addEventListener('click', hideDeleteUserModal);
    document.querySelector('.confirm-delete-close-modal').addEventListener('click', hideDeleteUserModal);

    // show Make user admin modal
    var adminModal = document.querySelector('.make-admin-modal-container');
    var adminModalShow = document.querySelector('.make-admin');
    adminModalShow.addEventListener('click', showAdminModalFunc);
    function showAdminModalFunc(){
        adminModal.classList.add('show');
    }

    document.querySelector('.admin-close-modal').addEventListener('click', hideAdminModal);
    document.querySelector('.admin-overlay-container').addEventListener('click', hideAdminModal);
    document.querySelector('.make-admin-cancel-btn').addEventListener('click', hideAdminModal);
    function hideAdminModal(){
        adminModal.classList.remove('show');
    }

    // show Transfer ownership to a user
    var ownershipModal = document.querySelector('.ownweship-modal-container');
    var ownershipModalShow = document.querySelector('.make-owner');
    ownershipModalShow.addEventListener('click', showOwnershipModalFunc);
    function showOwnershipModalFunc(){
        ownershipModal.classList.add('show');
    }

    document.querySelector('.ownweship-close-modal').addEventListener('click', hideOwnershipModal);
    document.querySelector('.ownweship-overlay-container').addEventListener('click', hideOwnershipModal);
    document.querySelector('.ownership-removal-btn').addEventListener('click', hideOwnershipModal);
    function hideOwnershipModal(){
        ownershipModal.classList.remove('show');
    }

    var radioList = document.querySelectorAll('input[name="transfer-ownership-check"]');
    for (var radio of radioList) {
        if (radio.checked) {
            console.log('Checked');
        }
    }

    var acceptTransferBtn = document.querySelector('.accept-transfer');
    var revokeTransferBtn = document.querySelector('.revoke-transfer');
    var owneshipTransferBanner = document.querySelector('.top-ownership-banner');

    acceptTransferBtn.addEventListener('click', hideTransferBanner);
    revokeTransferBtn.addEventListener('click', hideTransferBanner);

    function hideTransferBanner(){
        owneshipTransferBanner.classList.add('hide');
    }

    // Show user modal
    var userModal = document.querySelector('.make-user-modal-container');
    var userModalShow = document.querySelector('.make-user');
    userModalShow.addEventListener('click', showUserModalFunc);
    function showUserModalFunc(){
        userModal.classList.add('show');
    }

    document.querySelector('.user-close-modal').addEventListener('click', hideUserModal);
    document.querySelector('.user-overlay-container').addEventListener('click', hideUserModal);
    document.querySelector('.user-admin-cancel-btn').addEventListener('click', hideUserModal);
    function hideUserModal(){
        userModal.classList.remove('show');
    }

    // Switching between delete user and confirm delete user block on the modal
    var deleteUseBlock = document.querySelector('.delete-user-block');
    var confirmDeleteBtn = document.querySelector('.confirm-delete-btn');
    var confirmDeleteBlock = document.querySelector('.confirm-delete-block');

    confirmDeleteBtn.addEventListener('click', function(){
        deleteUseBlock.classList.add('hide');
        confirmDeleteBlock.classList.add('show');
    });

    function hideDeleteUserModal(){
        deleteModalContainer.classList.remove('show');
        confirmDeleteBlock.classList.remove('show');
        deleteUseBlock.classList.remove('hide');
    }

    var headings = document.querySelectorAll('.heading-app');
    for (var i = 0; i < headings.length; i++) {
        headings[i].addEventListener('click', handleHeadingClick);
    }

    function handleHeadingClick(event) {
        var heading = event.currentTarget;
        heading.nextElementSibling.classList.toggle('collapse');
        heading.querySelector('svg').classList.toggle('active');
    }

    var buttons = document.querySelectorAll('.toggle-app');
    for (var j = 0; j < buttons.length; j ++) {
        buttons[j].addEventListener('click', handleButtonClick);
    }

    function handleButtonClick(event) {
        this.parentNode.parentNode.classList.toggle('show')
    }

    var actions = document.querySelectorAll('.actions');
    for (var k = 0; k < actions.length; k ++) {
        actions[k].addEventListener('click', handleMenuClick);
    }

    function handleMenuClick() {
        var parent = this.parentNode.parentNode;

        parent.querySelector('.menu').classList.toggle('show');
        parent.querySelector('.modal').classList.toggle('show');
    }

    var modals = document.querySelectorAll('.modal');
    for (var l = 0; l < modals.length; l ++) {
        modals[l].addEventListener('click', function() {
            document.querySelector(".modal.show").classList.remove('show');
            document.querySelector(".menu.show").classList.remove('show');
        })
    }

    var deleteButtons = document.querySelectorAll('.app-delete');
    for (var m = 0; m < modals.length; m ++) {
        deleteButtons[m].addEventListener('click', handleDeleteMenuClick);
    }

    function handleDeleteMenuClick(event) {
        event.preventDefault();

        var app = event.currentTarget;

        var data = {
            name: app.dataset.name,
            _method: 'DELETE'
        };

        var url = '/apps/' + app.dataset.name;
        var xhr = new XMLHttpRequest();

        if(!confirm('Are you sure you want to delete this app?')) {
            document.querySelector(".menu.show").classList.remove('show');
            return;
        }

        xhr.open('POST', url, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');

            xhr.send(JSON.stringify(data));

            xhr.onload = function() {
                if (xhr.status === 200) {
                    window.location.href = "{{ route('app.index') }}";
                    addAlert('success', 'Application deleted successfully');
                }
            };

        document.querySelector(".menu.show").classList.remove('show');
    }

    var keys = document.querySelectorAll('.copy');
    for (var i = 0; i < keys.length; i ++) {
        keys[i].addEventListener('click', copyText);
    }

    function copyText() {
        var url = '/apps/' + this.dataset.reference + '/credentials/' + this.dataset.type;
        var xhr = new XMLHttpRequest();
        var btn = this;

        btn.className = 'copy loading';

        xhr.open('GET', url, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.send();

        xhr.onload = function() {
            if(xhr.status === 302 || /login/.test(xhr.responseURL)){
                addAlert('info', ['You are currently logged out.', 'Refresh the page to login again.']);
                btn.className = 'copy';
            } else if (xhr.status === 200) {
                var response = xhr.responseText ? JSON.parse(xhr.responseText) : null;

                if(response === null){
                    btn.className = 'copy';
                    return void addAlert('error', ['Sorry there was a problem getting the credentials', 'Please try again']);
                }

                copyToClipboard(response.credentials);
                btn.className = 'copy copied';
            }
        };
    }

    function copyToClipboard(text) {
        var dummy = document.createElement("textarea");
        dummy.style.position = 'absolute';
        document.body.appendChild(dummy);
        dummy.value = text;
        dummy.select();
        document.execCommand("copy");
        document.body.removeChild(dummy);
    }

</script>
@endpush
