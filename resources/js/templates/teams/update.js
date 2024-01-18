var closeTagBtnn = document.querySelector('.close-tag');
var logoFile = document.querySelector('.logo-file');
var fileName = document.querySelector('.upload-file-name');
var fileBtn = document.querySelector('.logo-add-icon');
var acceptTeamInvite = document.querySelector('.accept-team-invite');
var rejectTeamInvite = document.querySelector('.reject-team-invite');

logoFile.addEventListener('change', chooseTeamPicture);
function chooseTeamPicture() {
    var newFile = this.files[0].name.split('.');
    var extension = newFile[1];
    var filename = ''
    if (newFile[0].length > 20) { filename = newFile[0].substr(0, 20) + '...' + extension; }
    else { filename = newFile[0] + '.' + extension; }
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

    if (allowedTypes[inputAccepts] === undefined || allowedTypes[inputAccepts].indexOf(files[0].type) === -1) {
        addAlert("error", "The type of image you have chosen isn't supported. Please choose a jpg or png to upload");
    }
}

if (acceptTeamInvite) {
    acceptTeamInvite.addEventListener('click', function (event) {
        var data = {
            token: this.dataset.invitetoken,
            csrftoken: this.dataset.csrftoken
        };

        handleTimeInvite('/teams/accept', data, event);
    });
}

if (rejectTeamInvite) {
    rejectTeamInvite.addEventListener('click', function (event) {
        var data = {
            token: this.dataset.invitetoken,
            csrftoken: this.dataset.csrftoken
        };

        handleTimeInvite('/teams/reject', data, event);
    });
}

function handleTimeInvite(url, data, event) {
    var xhr = new XMLHttpRequest();

    event.preventDefault();

    xhr.open('POST', url);

    xhr.setRequestHeader('X-CSRF-TOKEN', data.csrftoken);
    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.send(JSON.stringify(data));

    addLoading('Handling team invite response.');

    xhr.onload = function () {
        if (xhr.status === 200) {
            document.querySelector('.top-invite-banner').classList.remove('show');
        } else {
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

            if (result.errors) {
                result.message = [];
                for (var error in result.errors) {
                    result.message.push(result.errors[error]);
                }
            }

            addAlert('error', result.message || 'Sorry there was a problem handling team invitation. Please try again.');
        }
        removeLoading();
    };
}

document.querySelector('#form-create-team').addEventListener('submit', createTeam);
function createTeam(event){
    event.preventDefault();
    var form = this.elements;
    var errors = [];
    var urlValue = form['url'].value;
    var teamName = form['name'].value;
    var contactNumber = form['contact'].value;
    var phoneRegex = /^\+|0(?:[0-9] ?){6,14}[0-9]$/;
    var teamId = form['team-id-value'].value;

    if(form['name'].value === ''){
        errors.push("Name required");
    }

    if(urlValue === ''){
        errors.push("Team URL required");
    } 
    
    if(urlValue !== '' && !/https?:\/\/.*\..*/.test(urlValue)) {
        errors.push('Please add a valid team url. Eg. https://url.com');
    }

    if(contactNumber === ''){
        errors.push("Contact number required");
    }
    
    if(contactNumber !== '' && !phoneRegex.test(contactNumber)){
        errors.push("Valid phone number required");
    }

    if(form['country'].value === ''){
        errors.push("Country required");
    }

    if (errors.length > 0) {
        return void addAlert('error', errors.join('<br>'));
    }

    var _token = form['_token'].value;
    var formData = new FormData();
    var updatedTeamLogo = document.getElementById("logo-file").files[0]

    formData.append('_token', _token);
    formData.append('name', teamName);
    formData.append('url', urlValue);
    formData.append('contact', contactNumber);
    formData.append('country', form['country'].value);
    formData.append('description', form['description'].value);
    formData.append('_method', 'PUT');

    if(updatedTeamLogo){
        formData.append('logo_file', updatedTeamLogo);
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', this.action, true);
    xhr.setRequestHeader('X-CSRF-TOKEN', _token);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.send(formData);
    addLoading('updating the team...');

    xhr.onload = function() {
        removeLoading();

        if (xhr.status === 200) {
            addAlert('success', [`Your team was succefully updated`], function(){
                window.location.href = `/teams/${teamId}/team`;
            });
        }
        else if(xhr.status === 424){
            addAlert('warning', ["You are not this team's admin."], function(){
                window.location.href = "/teams";
            });
        }
        else if(xhr.status === 304){
            addAlert('info', ["You did not make any changes."], function(){
                window.location.href = `/teams/${teamId}/team`;
            });
        }
        else {
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

            if(result.errors) {
                result.message = [];
                for(var error in result.errors){
                    result.message.push(result.errors[error]);
                }
            }
            addAlert('error', result.message || 'Sorry there was a problem creating your team. Please try again.');
        }
    };
}
