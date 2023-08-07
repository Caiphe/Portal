var modalContainer = document.querySelector('.mdp-dialog-box');
var leaveTeamBtn = document.querySelectorAll('.leave-team');
var cancelBtn = document.querySelector('.cancel-btn');
var teamNameText = document.querySelector('.team-name');
var leaveTeamForm = document.getElementById('form-team-leave');
var leaveTeamActionBtn = document.querySelector('.leave-team-btn');
var hiddenTeamId = document.querySelector('.hidden-team-id');
var hiddenTeamUserId = document.querySelector('.hidden-team-user-id');
var leaveTeamTransferBtn = document.querySelectorAll('.leave-team-transfer');

var ownershipModal = document.querySelector('.ownweship-modal-container');
var ownershipModalShow = document.querySelector('.make-owner');

for (var i = 0; i < leaveTeamTransferBtn.length; i++) {
    leaveTeamTransferBtn[i].addEventListener('click', showOwnershipModalFunc);
}

function showOwnershipModalFunc() {
    var eachteam = this.parentNode.parentNode;
    console.log(eachteam.previousSibling);

    eachteam.previousSibling.classList.add('show');
    // console.log('leave a team and transfer');
}


var radiosList = document.querySelectorAll('input[name="transfer-ownership-check"]');
for (var i = 0; i < radiosList.length; i++) {
    radiosList[i].addEventListener('click', checkedRadio);
}

function checkedRadio() {
    if (this.checked) {
        transferOwnsershipBtn.classList.remove('inactive');
        transferOwnsershipBtn.setAttribute('data-useremail', this.value);
    }
}

cancelBtn.addEventListener('click', hideModal);


for (var i = 0; i < leaveTeamBtn.length; i++) {
    leaveTeamBtn[i].addEventListener('click', showLeaveTeamModal);
}

function showLeaveTeamModal(){
    modalContainer.classList.add('show');

    teamNameText.innerHTML = this.dataset.teamname;

    //Hidden fields to track the Team being left
    hiddenTeamId.value = this.dataset.teamid;
    hiddenTeamUserId.value = this.dataset.teamuser;
}

function hideModal() {
    modalContainer.classList.remove('show');
}

leaveTeamActionBtn.addEventListener('click', function(event){
    var xhr = new XMLHttpRequest();
    var data = {
        team_id: hiddenTeamId.value,
        user_id: hiddenTeamUserId.value
    }
    var url = "teams/" + data.team_id + "/leave";

    event.preventDefault();

    addLoading('Leaving...');

    xhr.open('POST', url);
    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader("X-CSRF-TOKEN",
        document.getElementsByName("csrf-token")[0].content
    );

    xhr.send(JSON.stringify(data));

    xhr.onload = function() {
        if (xhr.status === 200) {

            modalContainer.classList.remove('show');

            addAlert('success', ['Team successfully left.', 'You will be redirected to your teams page shortly.'], function(){
                window.location.href = "/teams";
            });
        } else {
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

            if(result.errors) {
                result.message = [];
                for(var error in result.errors){
                    result.message.push(result.errors[error]);
                }
            }

            addAlert('error', result.message || 'Sorry there was a problem leaving team. Please try again.');
        }

        removeLoading();
    };

});


var btnAcceptInvite = document.querySelector('.accept-team-invite');
if(btnAcceptInvite){

    btnAcceptInvite.addEventListener('click', function (event){
        var data = {
            token: this.dataset.invitetoken,
        };

        handleInvite('/teams/accept', data, event);
    });
}

var btnRejectInvite =  document.querySelector('.reject-team-invite');
if(btnRejectInvite){
    btnRejectInvite.addEventListener('click', function (event){
        var data = {
            token: this.dataset.invitetoken,
        };

        handleInvite('/teams/reject', data, event);
    });
}


function handleInvite(url, data, event) {
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

    addLoading('Handling team invite response.');

    xhr.onload = function() {
        if (xhr.status === 200) {
            document.querySelector('.top-invite-banner').classList.remove('show');
            if(/reject/.test(url)){
                addAlert('success', "Thanks, for your response");
            } else {
               addAlert('success', ["Thanks, for your response", "The page will refresh shortly"], function(){
                    window.location.reload();
                });
            }
        } else {
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

            if(result.errors) {
                result.message = [];
                for(var error in result.errors){
                    result.message.push(result.errors[error]);
                }
            }

            addAlert('error', result.message || 'Sorry there was a problem handling team invitation. Please try again.');
        }

        removeLoading();
    };
}