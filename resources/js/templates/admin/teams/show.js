// Delete Team feature here
var deleteTeamBtn = document.querySelector('.delete-team-btn');
var blockActions = document.querySelectorAll('.block-actions');
var btnActions = document.querySelectorAll('.btn-action');
var hideMenuBlock = document.querySelectorAll('.block-hide-menu');
var hiddenTeamId = document.querySelector('.hidden-team-id');
var hiddenTeamUserId = document.querySelector('.hidden-team-user-id');


deleteTeamBtn.addEventListener('click', deleteTeam);
function deleteTeam(){
    var deleteModal = document.querySelector('.team-deletion-confirm');
    deleteModal.classList.add('show');
    var deleteForm = deleteModal.querySelector('.confirm-user-deletion-request-form');
    deleteForm.addEventListener('submit', confirmTeamDeletion);
}

function confirmTeamDeletion(ev){
    ev.preventDefault();
    var formToken = this.elements['_token'].value;
    var team_id = this.elements['team_id'].value;
    var team_name = this.elements['team_name'].value;

    var data = {
        _method: 'DELETE',
        _token: formToken,
        team: team_id
    }

    var xhr = new XMLHttpRequest();
    addLoading('Deletion in progress...');

    xhr.open('POST', this.action, true);
    xhr.setRequestHeader('X-CSRF-TOKEN', formToken);
    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    
    xhr.send(JSON.stringify(data));

    xhr.onload = function() {
        removeLoading();
        if (xhr.status === 200) {
            addAlert('success', [`Team ${team_name}  Deleted Successfully.`], function(){
                window.location.href = '/admin/teams';
            });
            return;

        } else {
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;
            if(result.errors) {
                result.message = [];
                for(var error in result.errors){
                    result.message.push(result.errors[error]);
                }
            }

            addAlert('error', result.message || 'Sorry there was a problem with your opco admin request. Please try again.');
        }
    };
}

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
    var blockHideMenuShow = document.querySelector('.block-hide-menu.show');
    var blockActionsShow = document.querySelector('.block-actions.show');

    if (blockHideMenuShow) {
        blockHideMenuShow.classList.remove('show');
    }
    if (blockActionsShow) {
        blockActionsShow.classList.remove('show');
    }
}

// Remove user from the team
// Show delete modal
var userDeleteBtn = document.querySelectorAll('.user-delete');
var deleteModalContainer = document.querySelector('.delete-modal-container');

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

document.querySelector('.cancel-removal-btn').addEventListener('click', hideDeleteUserModal);

// Switching between delete user and confirm delete user block on the modal
var deleteUseBlock = document.querySelector('.delete-user-block');
var confirmDeleteBtn = document.querySelector('.confirm-delete-btn');
var confirmDeleteBlock = document.querySelector('.confirm-delete-block');
var deleteUserActionBtn = document.querySelector('.remove-user-from-team');

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

deleteUserActionBtn.addEventListener('click', removeUser);
function removeUser(event){
    event.preventDefault();

    var data = {
        team_id: hiddenTeamId.value,
        user_id: hiddenTeamUserId.value
    }

    var url = "/teams/" + data.team_id + "/remove";
    var xhr = new XMLHttpRequest();

    addLoading('Removing user...');
    xhr.open('POST', url);

    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader("X-CSRF-TOKEN", 
        document.getElementsByName("csrf-token")[0].content
    );

    xhr.send(JSON.stringify(data));

    xhr.onload = function () {
        if (xhr.status === 200) {
            addAlert('success', ['User successfully removed from team.'], function () {
                window.location.reload()
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
        }

        removeLoading();
    };
};


// Show user modal & change user roles
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
    var userRole = userModal.querySelector('.dialog-heading');

    if(this.dataset.userrole !== 'team_user'){
        userRole.innerHTML = "Make administrator";
    }else{
        userRole.innerHTML = 'Make user';
    }

    userModal.querySelector('.team-head').textContent = textLookup[(this.dataset.userrole + '_header')];
    userModal.querySelector('.teammate-text strong').textContent = textLookup[(this.dataset.userrole + '_role')];
    userModal.classList.add('show');
}

function hideUserModal() {
    userModal.classList.remove('show');
}

function handleMakeUserRole(url, data, event) {
    event.preventDefault();

    var xhr = new XMLHttpRequest();
    var roleButton;

    var roleLookup = {
        'team_admin': 'Team admin',
        'team_user': 'Team user',
    };

    var textLookup = {
        'team_admin_header': 'Make user',
        'team_user_header': 'Make administrator',
        'team_admin_role': 'team_user',
        'team_user_role': 'team_admin',
    };

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
        removeLoading();

        if (xhr.status === 200) {
            roleButton = document.getElementById('change-role-' + data.user_id);

            addAlert('success', "User's role has been updated", function(){
                location.reload();
            });

            document.getElementById('team-role-' + data.user_id).textContent = roleLookup[data.role];
            roleButton.textContent = textLookup[data.role + '_header'];
            roleButton.dataset.userrole = textLookup[data.role + '_role'];

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
    };
}
