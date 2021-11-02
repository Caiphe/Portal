var btnActions = document.querySelectorAll('.btn-actions');
var hideMenuBlock = document.querySelectorAll('.block-hide-menu');
var blockActions = document.querySelectorAll('.block-actions');
var mainUserMenu = document.querySelector('.main-users-menu')
var clodeModal = document.querySelector('.close-modal');
var deleteClodeModal = document.querySelector('.delete-close-modal');
var modalContainer = document.querySelector('.modal-container');
var overlayContainer = document.querySelector('.overlay-container');
var deleteOverlayContainer = document.querySelector('.delete-overlay-container');
var cancelRemoveUserBtn = document.querySelector('.cancel-remove-user-btn');
var hiddenTeamId = document.querySelector('.hidden-team-id');
var hiddenTeamUserId = document.querySelector('.hidden-team-user-id');
var deleteUserActionBtn = document.querySelector('.remove-user-from-team');
var teamInviteUserBtn = document.querySelector('.invite-btn');
var transferOwnsershipBtn = document.querySelector('#transfer-btn');
var makeOwnershipBtn = document.querySelector('#make-owner-btn');
var addTeamMobile;
var addTeammateBtn;


for (var i = 0; i < btnActions.length; i++) {
    btnActions[i].addEventListener('click', showUserAction);
}

function showUserAction() {
    this.previousElementSibling.classList.add("show");
    this.nextElementSibling.classList.add('show');
}

for (var i = 0; i < hideMenuBlock.length; i++) {
    hideMenuBlock[i].addEventListener('click', hideActions);
}

function hideActions() {
    this.classList.remove('show');
    this.nextElementSibling.nextElementSibling.classList.remove('show');
}

if (addTeamMobile = document.querySelector('.add-team-mate-btn-mobile')) {
    addTeamMobile.addEventListener('click', hideModalContainer);
}

if (addTeammateBtn = document.querySelector('.add-team-mate-btn')) {
    addTeammateBtn.addEventListener('click', hideModalContainer);
}

function hideModalContainer() {
    modalContainer.classList.add('show');
}

clodeModal.addEventListener('click', hideAddTeamMateModalContainer);
overlayContainer.addEventListener('click', hideAddTeamMateModalContainer);

function hideAddTeamMateModalContainer() {
    modalContainer.classList.remove('show');
}

// Show delete modal
var userDeleteBtn = document.querySelectorAll('.user-delete');
var deleteModalContainer = document.querySelector('.delete-modal-container')
for (var d = 0; d < userDeleteBtn.length; d++) {
    userDeleteBtn[d].addEventListener('click', showUserDelete);
}

function showUserDelete() {
    deleteModalContainer.classList.add('show');
    document.querySelector('.user-delete-name').innerHTML = this.dataset.usernamedelete;
    document.querySelector('.user-to-delete-name').innerHTML = this.dataset.usernamedelete;

    hiddenTeamId.value = this.dataset.teamid;
    hiddenTeamUserId.value = this.dataset.teamuserid;
}

deleteClodeModal.addEventListener('click', hideDeleteUserModal);
deleteOverlayContainer.addEventListener('click', hideDeleteUserModal);
cancelRemoveUserBtn.addEventListener('click', hideDeleteUserModal);
document.querySelector('.cancel-removal-btn').addEventListener('click', hideDeleteUserModal);
document.querySelector('.confirm-delete-close-modal').addEventListener('click', hideDeleteUserModal);

// show Make admin modal
var adminModal = document.querySelector('.make-admin-modal-container');
var adminModalShow = document.querySelectorAll('.make-admin');

// show Transfer ownership to a user
var ownershipModal = document.querySelector('.ownweship-modal-container');
var ownershipModalShow = document.querySelector('.make-owner');


var radiosList = document.querySelectorAll('input[name="transfer-ownership-check"]');
for (var i = 0; i < radiosList.length; i++) {
    radiosList[i].addEventListener('click', checkedRadio);
}

function checkedRadio() {
    if (this.checked) {
        transferOwnsershipBtn.classList.remove('transfer-btn');
        transferOwnsershipBtn.setAttribute('data-useremail', this.value);
    }
}

var owneshipTransferBanner = document.querySelector('.top-ownership-banner');

/** Picking on the availability of this component */
var acceptTransferBtn = document.querySelector('.accept-transfer');
if (acceptTransferBtn) {
    acceptTransferBtn.addEventListener('click', hideTransferBanner);
}

/** Picking on the availability of this component */
var revokeTransferBtn = document.querySelector('.revoke-transfer');
if (revokeTransferBtn) {
    revokeTransferBtn.addEventListener('click', hideTransferBanner);
}

function hideTransferBanner() {
    owneshipTransferBanner.classList.add('hide');
}

// Show user modal
var makeUserId = document.querySelector('#each-user-id');
var makeTeamId = document.querySelector('#each-team-id');
var makeUserRole = document.querySelector('#each-user-role');

var userModal = document.querySelector('.make-user-modal-container');
var makeUserBtn = document.querySelector('.make-user-btn');
var userModalShow = document.querySelectorAll('.make-user');

for (var u = 0; u < userModalShow.length; u++) {
    userModalShow[u].addEventListener('click', showUserModalFunc);
}

if (makeUserBtn) {
    makeUserBtn.addEventListener('click', function (event) {
        var data = {
            'user_id': makeUserId.value,
            'team_id': makeTeamId.value,
            'role': makeUserRole.value
        };

        document.querySelector('.block-hide-menu.show').click();

        handleMakeUserRole('/teams/' + data.team_id + '/user/role', data, event);
    })
}
function handleMakeUserRole(url, data, event) {
    var xhr = new XMLHttpRequest();
    var roleButton;
    var roleLookup = {
        'team_admin': 'Team Admin',
        'team_user': 'Team User',
    };
    var textLookup = {
        'team_admin_header': 'Make User',
        'team_user_header': 'Make Administrator',
        'team_admin_role': 'team_user',
        'team_user_role': 'team_admin',
    };

    event.preventDefault();

    xhr.open('POST', url);
    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader(
        "X-CSRF-TOKEN",
        document.getElementsByName("csrf-token")[0].content
    );

    xhr.send(JSON.stringify(data));

    addLoading("Updating user's role.");

    xhr.onload = function () {
        if (xhr.status === 200) {
            roleButton = document.getElementById('change-role-' + data.user_id);

            addAlert('success', "User's role has been updated");

            document.getElementById('team-role-' + data.user_id).textContent = roleLookup[data.role];
            roleButton.textContent = textLookup[data.role + '_header'];
            roleButton.dataset.userrole = textLookup[data.role + '_role'];

            hideUserModal();
        } else {
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

            if (result.errors) {
                result.message = [];
                for (var error in result.errors) {
                    result.message.push(result.errors[error]);
                }
            }
            addAlert('error', result.message || 'Something went wrong. Please try again.');
        }

        removeLoading();
    };
}

function showUserModalFunc() {
    var textLookup = {
        'team_admin_header': 'Make Administrator',
        'team_user_header': 'Make User',
        'team_admin_role': 'administrator',
        'team_user_role': 'user',
    };
    makeUserId.value = this.dataset.teamuserid;
    makeTeamId.value = this.dataset.teamid;
    makeUserRole.value = this.dataset.userrole;

    document.querySelector('.make-user-name').textContent = this.dataset.username;

    userModal.querySelector('.team-head').textContent = textLookup[(this.dataset.userrole + '_header')];
    userModal.querySelector('.teammate-text strong').textContent = textLookup[(this.dataset.userrole + '_role')];
    userModal.classList.add('show');
}

document.querySelector('.user-close-modal').addEventListener('click', hideUserModal);
document.querySelector('.user-overlay-container').addEventListener('click', hideUserModal);
document.querySelector('.user-admin-cancel-btn').addEventListener('click', hideUserModal);

function hideUserModal() {
    userModal.classList.remove('show');
}

// Switching between delete user and confirm delete user block on the modal
var deleteUseBlock = document.querySelector('.delete-user-block');
var confirmDeleteBtn = document.querySelector('.confirm-delete-btn');
var confirmDeleteBlock = document.querySelector('.confirm-delete-block');

/** Resolves an issue with removing user from team */
if (confirmDeleteBtn !== null) {
    confirmDeleteBtn.addEventListener('click', function () {
        deleteUseBlock.classList.add('hide');
        confirmDeleteBlock.classList.add('show');
    });
}

function hideDeleteUserModal() {
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
for (var j = 0; j < buttons.length; j++) {
    buttons[j].addEventListener('click', handleButtonClick);
}

function handleButtonClick(event) {
    this.parentNode.parentNode.classList.toggle('show')
}

var actions = document.querySelectorAll('.actions');
for (var k = 0; k < actions.length; k++) {
    actions[k].addEventListener('click', handleMenuClick);
}

function handleMenuClick() {
    var parent = this.parentNode.parentNode;
    parent.querySelector('.menu').classList.toggle('show');
    parent.querySelector('.modal').classList.toggle('show');
}

var modals = document.querySelectorAll('.modal');
for (var l = 0; l < modals.length; l++) {
    modals[l].addEventListener('click', function () {
        document.querySelector(".modal.show").classList.remove('show');
        document.querySelector(".menu.show").classList.remove('show');
    })
}

var deleteButtons = document.querySelectorAll('.app-delete');
for (var m = 0; m < modals.length; m++) {
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

    if (!confirm('Are you sure you want to delete this app?')) {
        document.querySelector(".menu.show").classList.remove('show');
        return;
    }

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader("X-CSRF-TOKEN",
        document.getElementsByName("csrf-token")[0].content
    );

    xhr.send(JSON.stringify(data));

    xhr.onload = function () {
        if (xhr.status === 200) {
            window.location.href = "{{ route('app.index') }}";
            addAlert('success', 'Application deleted successfully');
        }
    };

    document.querySelector(".menu.show").classList.remove('show');
}

var keys = document.querySelectorAll('.copy');
for (var i = 0; i < keys.length; i++) {
    keys[i].addEventListener('click', copyText);
}

function copyText() {
    var url = '/apps/' + this.dataset.reference + '/credentials/' + this.dataset.type;
    var xhr = new XMLHttpRequest();
    var btn = this;

    btn.className = 'copy loading';

    xhr.open('GET', url, true);

    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader("X-CSRF-TOKEN",
        document.getElementsByName("csrf-token")[0].content
    );

    xhr.send();

    xhr.onload = function () {
        if (xhr.status === 302 || /login/.test(xhr.responseURL)) {
            addAlert('info', ['You are currently logged out.', 'Refresh the page to login again.']);
            btn.className = 'copy';
        } else if (xhr.status === 200) {
            var response = xhr.responseText ? JSON.parse(xhr.responseText) : null;

            if (response === null) {
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

deleteUserActionBtn.addEventListener('click', function (event) {
    var url = "/teams/leave";
    var xhr = new XMLHttpRequest();
    var data = {
        team_id: hiddenTeamId.value,
        user_id: hiddenTeamUserId.value
    }

    event.preventDefault();

    addLoading('Leaving...');

    xhr.open('POST', url);

    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader("X-CSRF-TOKEN",
        document.getElementsByName("csrf-token")[0].content
    );

    xhr.send(JSON.stringify(data));

    xhr.onload = function () {
        if (xhr.status === 200) {
            addAlert('success', ['User successfully removed from team.', 'Please wait meanwhile the page refreshes.'], function () {
                if (window.history) {
                    history.back();
                }
            });
        } else {
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

            if (result.errors) {
                result.message = [];
                for (var error in result.errors) {
                    result.message.push(result.errors[error]);
                }
            }

            addAlert('error', result.message || 'Sorry there was a problem removing team. Please try again.');
            setTimeout(function () {
                console.log("Hide the modal");
            }, 4000);
        }

        removeLoading();
    };
});

var teamMateInvitEmail = document.querySelector('.teammate-email');
var inviteTeamMateError = document.querySelector('.teammate-error-message');

var mailformat = /^[\w\.\-\+]+@[\w\.\-]+\.[a-z]{2,5}$/;

teamMateInvitEmail.value = "";
var timer = null;

teamMateInvitEmail.addEventListener('input', function(){
    clearTimeout(timer);
    timer = setTimeout(emailCheck, 1000);
});

teamMateInvitEmail.addEventListener('keyup', emailCheck);

function emailCheck() {
    var inviteTeamMateError = document.querySelector('.teammate-error-message');
    var mailformat = /^[\w\.\-\+]+@[\w\.\-]+\.[a-z]{2,5}$/;

    if (teamMateInvitEmail.value.match(mailformat)) {
        inviteTeamMateError.classList.remove('show');
        teamInviteUserBtn.classList.add('active');
    } else {
        inviteTeamMateError.classList.add('show');
        teamInviteUserBtn.classList.remove('active');
    }
}

teamInviteUserBtn.addEventListener('click', function (event) {
    var url = "/teams/" + this.dataset.teamid + "/invite";
    var xhr = new XMLHttpRequest();
    var data = {
        role: document.querySelector('input[name=role_name]:checked').value,
        invitee: document.querySelector('.teammate-email').value,
        team_id: this.dataset.teamid
    };

    event.preventDefault();

    xhr.open('POST', url);

    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader("X-CSRF-TOKEN",
        document.getElementsByName("csrf-token")[0].content
    );

    xhr.send(JSON.stringify(data));

    xhr.onload = function () {
        if (xhr.status === 200) {
            addAlert('success', ['User was successfully invited to the team.', 'Please wait meanwhile the page refreshes.'], function () {
                if (window.history) {
                    history.back();
                }
            });
        } else {
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

            if (result.errors) {
                result.message = [];
                for (var error in result.errors) {
                    result.message.push(result.errors[error]);
                }
            }

            addAlert('error', result.message || 'Sorry there was a problem inviting the user to the team. Please try again.');
        }

        hideUserModal();
    };

    teamMateInvitEmail.value = "";
    setTimeout(hideAddTeamMateModalContainer(), 2000);
});

if (document.querySelector('.transfer-ownership')) {
    document.querySelector('.transfer-ownership').addEventListener('click', function (event) {
        var url = "/teams/" + this.dataset.teamid + "/ownership";
        var xhr = new XMLHttpRequest();
        var data = {
            type: 'ownership',
            invitee: this.dataset.useremail,
            team_id: this.dataset.teamid
        };

        event.preventDefault();

        xhr.open('POST', url);
        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader("X-CSRF-TOKEN",
            document.getElementsByName("csrf-token")[0].content
        );

        xhr.send(JSON.stringify(data));

        xhr.onload = function () {
            if (xhr.status === 200) {
                addAlert('success', 'Team transfer ownership requested to member.');
            } else {
                var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

                if (result.errors) {
                    result.message = [];
                    for (var error in result.errors) {
                        result.message.push(result.errors[error]);
                    }
                }

                addAlert('error', result.message || 'Sorry there was a problem requesting ownership from the team member. Please try again.');
            }
        };
    });
}

var btnAcceptOwnership = document.querySelector('.accept-team-ownership');
if (btnAcceptOwnership) {
    btnAcceptOwnership.addEventListener('click', function (event) {
        var data = {
            token: this.dataset.invitetoken,
        };

        handleOwnershipTransfer('/teams/accept', data, event);
    });
}


var btnRejectOwnership = document.querySelector('.reject-team-ownership');
if (btnRejectOwnership) {
    btnRejectOwnership.addEventListener('click', function (event) {
        var data = {
            token: this.dataset.invitetoken,
        };

        handleOwnershipTransfer('/teams/reject', data, event);
    });
}


transferOwnsershipBtn.addEventListener('click', function (event) {
    var data = {
        team_id: this.dataset.teamid,
        invitee: this.dataset.useremail
    };
    handleOwnershipTransfer('/teams/' + this.dataset.teamid + '/ownership', data, event);
});

makeOwnershipBtn.addEventListener('click', function (event) {
    var data = {
        team_id: this.dataset.teamid,
        invitee: this.dataset.useremail
    };
    handleOwnershipTransfer('/teams/' + this.dataset.teamid + '/ownership', data, event);
});


function handleOwnershipTransfer(url, data, event) {
    var xhr = new XMLHttpRequest();

    event.preventDefault();

    xhr.open('POST', url);
    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader(
        "X-CSRF-TOKEN",
        document.getElementsByName("csrf-token")[0].content
    );

    xhr.send(JSON.stringify(data));

    addLoading('Sending ownership request for team.');

    xhr.onload = function () {
        if (xhr.status === 200) {
            addAlert('success', "Ownership trafer request sent successfully !");
            hideOwnershipModal();
            hideAdminModal();
        } else {
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

            if (result.errors) {
                result.message = [];
                for (var error in result.errors) {
                    result.message.push(result.errors[error]);
                }
            }

            addAlert('error', result.message || 'Sorry there was a problem requesting ownership from the team member. Please try again.');
        }

        removeLoading();
    };
}


/** Fix a bug when attempting to remove/delete a User from Team */
if (ownershipModalShow !== null) {
    ownershipModalShow.addEventListener('click', showOwnershipModalFunc);
}

function showOwnershipModalFunc() {
    ownershipModal.classList.add('show');
}

document.querySelector('.ownweship-close-modal').addEventListener('click', hideOwnershipModal);
document.querySelector('.ownweship-overlay-container').addEventListener('click', hideOwnershipModal);
document.querySelector('.ownership-removal-btn').addEventListener('click', hideOwnershipModal);
function hideOwnershipModal() {
    ownershipModal.classList.remove('show');
}

// This hides and shows the ownership modal from the user's list
for (var i = 0; i < adminModalShow.length; i++) {
    adminModalShow[i].addEventListener('click', showAdminModalFunc);
}

function showAdminModalFunc() {
    var makeOwnerBtn = document.querySelector('#make-owner-btn');

    document.querySelector('.admin-user-name').innerHTML = this.dataset.adminname;

    makeOwnerBtn.setAttribute('data-useremail', this.dataset.useremail)
    makeOwnerBtn.setAttribute('data-userrole', this.dataset.userrole);

    adminModal.classList.add('show');
}

document.querySelector('.admin-close-modal').addEventListener('click', hideAdminModal);
document.querySelector('.admin-overlay-container').addEventListener('click', hideAdminModal);
document.querySelector('.make-admin-cancel-btn').addEventListener('click', hideAdminModal);
function hideAdminModal() {
    adminModal.classList.remove('show');
}
