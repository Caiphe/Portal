var invitationInput = document.querySelector('.invitation-field');
var inviteBtn = document.querySelector('.invite-btn');
var closeTagBtnn = document.querySelector('.close-tag');
var teamForm = document.querySelector('#form-create-team');
var tagList = [];
var timer = null;

invitationInput.addEventListener('input', function () {
    clearTimeout(timer);
    timer = setTimeout(invitationEmailCheck, 1000);
});

document.querySelector('#team-name').addEventListener('keyup', removeSpecialCharacters);
function removeSpecialCharacters(){
    var specialChrs = /[`~!)@#$%(^&*|+=?;:±§'",.<>\{\}\[\]\\\/]/gi;
    this.value = this.value.replace(/  +/g, ' ');

    if(specialChrs.test(this.value)){
        this.value = this.value.replace(specialChrs, '');
        addAlert('warning', 'Team name cannot contain special characters.');
    }
}

function invitationEmailCheck() {
    var mailformat = /^[\w\.\-\+]+@[\w\.\-]+\.[a-z]{2,5}$/;
    var errorMsg = document.querySelector('.error-email');

    if (invitationInput.value.match(mailformat)) {
        inviteBtn.classList.add('active');
        errorMsg.classList.remove('show');

    } else {
        inviteBtn.classList.remove('active');
        errorMsg.classList.add('show');
    }
}

function createSingleTag(email) {
    var tagSpan = document.createElement('span');
    var hiddenInput = document.createElement('input');
    var closeTagBtn = document.createElement('button');

    tagSpan.setAttribute('class', 'each-tag');
    tagSpan.textContent = email;

    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'team_members[]');
    hiddenInput.setAttribute('value', email);

    closeTagBtn.setAttribute('type', 'button');
    closeTagBtn.setAttribute('class', 'close-tag');
    closeTagBtn.setAttribute('data-email', email);

    closeTagBtn.addEventListener('click', clearTag);

    tagSpan.appendChild(hiddenInput);
    tagSpan.appendChild(closeTagBtn);

    return tagSpan;
}

function clearTag() {
    var email = this.dataset.email;
    tagList = tagList.filter(function (item) {
        return item !== email;
    })
    this.parentNode.remove();
}

inviteBtn.addEventListener('click', createEmailTags);

function createEmailTags() {
    var email = invitationInput.value;
    if (tagList.indexOf(email) !== -1) {
        return void addAlert("error", "Email exists already.");
    }

    tagList.push(email);

    var tag = createSingleTag(email);
    var inviteContainer = document.getElementById('invite-list');
    inviteContainer.classList.add('m-40');
    inviteContainer.append(tag);
    invitationInput.value = "";
    this.classList.remove('active');
}

var logoFile = document.querySelector('.logo-file');
var fileName = document.querySelector('.upload-file-name');
var fileBtn = document.querySelector('.logo-add-icon');

logoFile.addEventListener('change', chooseTeamPicture);
async function chooseTeamPicture() {
    var newFile = this.files[0].name.split('.');
    var extension = newFile[1];
    var filename = newFile[0].length > 20 ? newFile[0].substr(0, 20) + '...' + extension : newFile[0] + '.' + extension;
    var files = this.files;
    var inputAccepts = this.getAttribute("accept");
    var imageDimentions = {};
    var allowedTypes = {
        "image/*": [
            "image/jpeg",
            "image/jpg",
            "image/png"
        ]
    }

    if (files.length > 1) {
        return void addAlert("error", "You can only add one profile picture");
    }

    if (files[0].size > 5242880) {
        return void addAlert("error", "Max file size is 5MB");
    }

    await getImageDimentions(files[0]).then((dim) => imageDimentions = dim);
    if (imageDimentions.width > 2000 || imageDimentions.height > 2000) {
        return void addAlert("error", "The width and height have a max size of 2000");
    }

    if (!(allowedTypes[inputAccepts] !== undefined && allowedTypes[inputAccepts].indexOf(files[0].type) !== -1)) {
        return void addAlert("error", "The type of image you have chosen isn't supported. Please choose a jpg or png to upload");
    }

    fileName.innerHTML = filename
    fileBtn.classList.add('active');
}

function getImageDimentions(image) {
    return new Promise(function (resolve) {
        var fr = new FileReader;

        fr.onload = function () {
            var img = new Image;

            img.onload = function () {
                resolve({ width: img.width, height: img.height });
            };

            img.src = fr.result;
        };

        fr.readAsDataURL(image); 
    });
}

var btnAcceptInvite = document.querySelector('.accept-team-invite');
if (btnAcceptInvite) {
    btnAcceptInvite.addEventListener('click', function (event) {
        var data = {
            token: this.dataset.invitetoken,
        };

        handleTimeInvite('/teams/accept', data, event);
    });
}

var btnRejectInvite = document.querySelector('.reject-team-invite')
if (btnRejectInvite) {
    btnRejectInvite.addEventListener('click', function (event) {
        var data = {
            token: this.dataset.invitetoken,
        };

        handleTimeInvite('/teams/reject', data, event);
    });
}

function handleTimeInvite(url, data, event) {
    var xhr = new XMLHttpRequest();

    event.preventDefault();

    xhr.open('POST', url);
    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader("X-CSRF-TOKEN",
        document.getElementsByName("csrf-token")[0].content
    );

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
