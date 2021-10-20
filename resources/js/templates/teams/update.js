var closeTagBtnn = document.querySelector('.close-tag');

var logoFile = document.querySelector('.logo-file');
var fileName = document.querySelector('.upload-file-name');
var fileBtn = document.querySelector('.logo-add-icon');

logoFile.addEventListener('change', chooseTeamPicture);
function chooseTeamPicture() {
    var newFile = this.files[0].name.split('.');
    var extension = newFile[1];
    var filename = ''
    if(newFile[0].length > 20){filename = newFile[0].substr(0, 20) + '...' + extension;}
    else{filename = newFile[0] + '.'+ extension;}
    fileName.innerHTML = filename
    fileBtn.classList.add('active');

    var files = this.files;
    var inputAccepts = this.getAttribute("accept");
    var allowedTypes = {
        "image/*": [
            "image/jpeg",
            "image/jpg",
            "image/png"
        ]
    }

    if (files.length > 1) {
        return void alert("You can only add one profile picture");
    }

    if (allowedTypes[inputAccepts] !== undefined && allowedTypes[inputAccepts].indexOf(files[0].type) !== -1) {
    console.log(allowedTypes);

    } else {
        addAlert("error", "The type of image you have chosen isn't supported. Please choose a jpg or png to upload");
    }
}

document.querySelector('.accept-team-invite').addEventListener('click', function (event){
    var data = {
        token: this.dataset.invitetoken,
        csrftoken: this.dataset.csrftoken
    };

    handleTimeInvite('/teams/accept', data, event);
});

document.querySelector('.reject-team-invite').addEventListener('click', function (event){
    var data = {
        token: this.dataset.invitetoken,
        csrftoken: this.dataset.csrftoken
    };

    handleTimeInvite('/teams/reject', data, event);
});

function handleTimeInvite(url, data, event) {
    var xhr = new XMLHttpRequest();

    event.preventDefault();

    xhr.open('POST', url);

    xhr.setRequestHeader('X-CSRF-TOKEN', data.csrftoken);
    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.send(JSON.stringify(data));

    addLoading('Handling team invite response.');

    xhr.onload = function() {
        if (xhr.status === 200) {
            document.querySelector('.top-invite-banner').classList.remove('show');
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
