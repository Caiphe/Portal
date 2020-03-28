document.getElementById('profile-picture').addEventListener('change', chooseProfilePicture);

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
    }

    reader.readAsDataURL(file);
}

function uploadProfilePicture(file) {
    var xhr = new XMLHttpRequest();

    if (!xhr) {
        console.log("Giving up :( Cannot create an XMLHTTP instance");
        return false;
    }

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

    var formData = new FormData();
    formData.append('profile', file);

    xhr.open("PUT", "/profile/update/picture");
    xhr.setRequestHeader(
        "X-CSRF-TOKEN",
        document.getElementsByName("csrf-token")[0].content
    );

    xhr.send(formData);
}
