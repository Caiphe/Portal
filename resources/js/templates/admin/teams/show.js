// Delete Team feature here
var deleteTeamBtn = document.querySelector('.delete-team-btn');
deleteTeamBtn.addEventListener('click', deleteTeam);

function deleteTeam(){
    var deleteModal = document.querySelector('.team-deletion-confirm');
    deleteModal.classList.add('show');
    var deleteForm = deleteModal.querySelector('.confirm-user-deletion-request-form');
    deleteForm.addEventListener('submit', confirmDelete);
}

function confirmDelete(ev){
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