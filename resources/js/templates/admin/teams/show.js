// Delete Team feature here
var deleteTeamBtn = document.querySelector('.delete-team-btn');
var blockActions = document.querySelectorAll('.block-actions');
var btnActions = document.querySelectorAll('.btn-action');
var hideMenuBlock = document.querySelectorAll('.block-hide-menu');
var hiddenTeamId = document.querySelector('.hidden-team-id');
var hiddenTeamUserId = document.querySelector('.hidden-team-user-id');
var makeOwnershipBtn = document.querySelector('#make-owner-btn');

deleteTeamBtn.addEventListener('click', deleteTeam);

function deleteTeam() {
    var deleteModal = document.querySelector('.team-deletion-confirm');
    deleteModal.classList.add('show');
    var deleteForm = deleteModal.querySelector('.confirm-user-deletion-request-form');
    deleteForm.addEventListener('submit', confirmTeamDeletion);
}

function confirmTeamDeletion(ev) {
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

    xhr.onload = function () {
        removeLoading();
        if (xhr.status === 200) {
            addAlert('success', [`Team ${team_name}  Deleted Successfully.`], function () {
                window.location.href = '/admin/teams';
            });
            return;

        } else {
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;
            if (result.errors) {
                result.message = [];
                for (var error in result.errors) {
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

function removeUser(event) {
    event.preventDefault();
    var deleteUserForm = document.querySelector('#remove-user-form');

    var data = {
        team_id: hiddenTeamId.value,
        user_id: hiddenTeamUserId.value
    }

    var xhr = new XMLHttpRequest();

    addLoading('Removing user...');
    xhr.open('POST', deleteUserForm.action);

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
        var changeUserRole = document.querySelector('#make-user-form');

        handleMakeUserRole(changeUserRole.action, data, event);
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

    if (this.dataset.userrole !== 'team_user') {
        userRole.innerHTML = "Make administrator";
    } else {
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

            addAlert('success', "User's role has been updated", function () {
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


//================================================Invite Teammate code========================================================
document.addEventListener('DOMContentLoaded', () => {
    const addTeammateBtn = document.querySelector('.add-team-mate-btn');
    const addTeammateDialog = document.querySelector('.add-teammate-dialog');
    const teamMateInviteEmail = document.querySelector('.teammate-email');
    const teamInviteUserBtn = document.querySelector('.invite-btn');
    const dropdown = document.createElement('div');

    dropdown.classList.add('email-dropdown');
    teamMateInviteEmail.parentNode.appendChild(dropdown);
    let timer = null;

    if (addTeammateBtn) {
        addTeammateBtn.addEventListener('click', () => {
            addTeammateDialog.classList.add('show');
            teamMateInviteEmail.value = "";
            dropdown.innerHTML = "";
        });
    }

    teamMateInviteEmail.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            const email = teamMateInviteEmail.value;
            if (email.length >= 2) {
                fetchDevelopers(email);
            }
        }, 100);
    });

    document.querySelector('.close-add-teammate-btn').addEventListener('click', hideTeammateModal);

    function hideTeammateModal() {
        addTeammateDialog.classList.remove('show');
    }


    function fetchDevelopers(email) {
        const url = teamMateInviteEmail.dataset.url;
        const data = {email: email};

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.getElementsByName('csrf-token')[0].content
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(developers => {
                updateDropdown(developers);
            })
            .catch(error => {
                console.error('Error fetching developers:', error);
                dropdown.innerHTML = "";
            });
    }

    function updateDropdown(developers) {
        dropdown.innerHTML = "";
        if (developers.length > 0) {
            developers.forEach(dev => {
                const item = document.createElement('div');
                item.classList.add('dropdown-item');
                item.textContent = dev.email;
                item.addEventListener('click', () => {
                    teamMateInviteEmail.value = dev.email;
                    dropdown.innerHTML = "";
                    teamInviteUserBtn.classList.add('active');
                });
                dropdown.appendChild(item);
            });
        } else {
            const noResults = document.createElement('div');
            noResults.classList.add('dropdown-item');
            noResults.textContent = 'No results found';
            dropdown.appendChild(noResults);
        }
    }

    teamInviteUserBtn.addEventListener('click', function (event) {
        event.preventDefault();
        if (!teamInviteUserBtn.classList.contains('active')) {
            addAlert('warning', 'Please enter a valid email address.');
            return;
        }

        const url = this.dataset.url;
        const teamId = this.dataset.teamid;
        const email = teamMateInviteEmail.value;
        const data = {
            role: document.querySelector('input[name=role_name]:checked').value,
            invitee: teamMateInviteEmail.value,
            team_id: teamId
        };

        addLoading('Inviting...');

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.getElementsByName('csrf-token')[0].content
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json().then(result => ({
                status: response.status,
                ok: response.ok,
                result
            })))
            .then(({status, ok, result}) => {
                if (status === 200) {
                    addAlert('success', `${email} has been invited to the team.`);
                } else if (status === 404) {
                    addAlert('error', 'The user is not found.');
                } else if (status === 403) {
                    addAlert('error', 'User is already a member of this team.');
                } else {
                    addAlert('error', result.message || 'Sorry there was a problem inviting the user to the team. Please try again.');
                }
                removeLoading();
                addTeammateDialog.classList.remove('show');
            })
            .catch(() => {
                addAlert('error', 'Sorry there was a problem inviting the user to the team. Please try again.');
                removeLoading();
                addTeammateDialog.classList.remove('show');
            });

        teamMateInviteEmail.value = "";
    });
});

//================================================Change Ownership code========================================================
document.addEventListener('DOMContentLoaded', function () {
    const transferOwnershipBtn = document.querySelector('#transfer-btn');
    const ownershipModal = document.querySelector('.ownweship-modal-container');
    const ownershipModalShow = document.querySelector('.make-owner');
    const radiosList = document.querySelectorAll('input[name="transfer-ownership-check"]');

    radiosList.forEach(radio => {
        radio.addEventListener('click', function () {
            if (this.checked) {
                transferOwnershipBtn.classList.remove('inactive');
                transferOwnershipBtn.setAttribute('data-userid', this.value);
            }
        });
    });

    if (ownershipModalShow !== null) {
        ownershipModalShow.addEventListener('click', showOwnershipModalFunc);
    }

    function showOwnershipModalFunc() {
        ownershipModal.classList.add('show');
    }

    document.querySelector('.ownership-removal-btn').addEventListener('click', hideOwnershipModal);

    function hideOwnershipModal() {
        setTimeout(() => {
            ownershipModal.classList.remove('show');
        }, 2000);
    }

    transferOwnershipBtn.addEventListener('click', function (event) {
        const url = this.dataset.url;
        const data = {
            team_id: this.dataset.teamid,
            new_owner_id: this.dataset.userid
        };
        handleRequestOwnershipTransfer(url, data, event);
    });

    function handleRequestOwnershipTransfer(url, data, event) {
        event.preventDefault();
        addLoading('Sending ownership request...');

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.getElementsByName("csrf-token")[0].content
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json()
                .then(result => ({
                    status: response.status,
                    ok: response.ok,
                    result
                })))
            .then(({ status, result }) => {
                if (status === 200) {
                    addAlert('success', result.message);
                    hideOwnershipModal();
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                } else if (status === 404) {
                    addAlert('error', 'The user is not found.');
                }

                removeLoading();
            })
            .catch(() => {
                addAlert('error', 'Sorry there was a problem inviting the user to the team. Please try again.');
                hideOwnershipModal();
                removeLoading();
            });
    }
});
