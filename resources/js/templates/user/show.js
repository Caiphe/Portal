document.getElementById('profile-picture').addEventListener('change', chooseProfilePicture);
document.querySelector('.enable-2fa-button').addEventListener('click', enable2FA);

function chooseProfilePicture(ev) {
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

    if (allowedTypes[inputAccepts].indexOf(files[0].type) !== -1) {
        changeProfilePicture(files[0]);
        uploadProfilePicture(files[0]);
    }
}

function changeProfilePicture(file) {
    var reader = new FileReader();

    reader.onload = function(e) {
        document.getElementById('profile-picture-label').style.backgroundImage = "url(" + e.target.result + ")";
        document.getElementById('profile-menu-picture').style.backgroundImage = "url(" + e.target.result + ")";
    }

    reader.readAsDataURL(file);
}

function uploadProfilePicture(file) {
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const result = xhr.responseText ? JSON.parse(xhr.responseText) : null;
                console.log(result);
            } else {
                console.log('Error');
                console.log(xhr.responseText);
            }
        }
    };

    xhr.open("POST", "/profile/update/picture");
    xhr.setRequestHeader(
        "X-CSRF-TOKEN",
        document.getElementsByName("csrf-token")[0].content
    );

    var formData = new FormData();
    formData.append('profile', file);

    xhr.send(formData);
}

function togglePasswordVisibility(that) {
    that.parentNode.classList.toggle('password-visible');
    that.previousElementSibling.setAttribute('type',that.parentNode.classList.contains('password-visible') ? "text" : "password");
}

function enable2FA() {
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const result = xhr.responseText ? JSON.parse(xhr.responseText) : null;
                console.log(result);
                document.querySelector('.enable-2fa').classList.add('show');
            } else {
                console.log('Error');
                console.log(xhr.responseText);
            }
        }
    };

    xhr.open("POST", "/profile/2fa/enable");
    xhr.setRequestHeader(
        "X-CSRF-TOKEN",
        document.getElementsByName("csrf-token")[0].content
    );
    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.send(JSON.stringify({
        key: this.dataset.key
    }));
}
