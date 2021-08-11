(function() {
    var uploader = document.getElementById('uploader');

    document.getElementById('new-tab').addEventListener('click', newTab);
    document.getElementById('uploader-input').addEventListener('change', chooseSwagger);
    document.getElementById('admin-form').addEventListener('submit', validateForm);

    ["dragenter", "dragover", "dragleave", "drop"].forEach(preventDefaultsListeners);
    ["dragenter", "dragover"].forEach(highlightListeners);

    uploader.addEventListener("dragleave", unhighlight, false);
    uploader.addEventListener("drop", verifyOpenApi, false);

    function preventDefaultsListeners(eventName) {
        uploader.addEventListener(eventName, preventDefaults, false);
    }

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlightListeners(eventName) {
        uploader.addEventListener(eventName, highlight, false);
    }

    function highlight() {
        uploader.classList.add("highlight");
    }

    function unhighlight() {
        uploader.classList.remove("highlight");
    }

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function chooseSwagger() {
        verifyOpenApi({ dataTransfer: { files: this.files } });
    }

    function verifyOpenApi(e) {
        var files = e.dataTransfer.files;
        var errors = [];

        unhighlight();
        uploader.querySelector('.errors').innerHTML = "";

        if (files.length > 1) {
            errors.push("You can only add one file.");
        }

        if (!/\.yml$|\.yaml$/.test(files[0].name)) {
            errors.push("The file isn't the correct type.");
        }

        if (errors.length > 0) {
            uploader.querySelector('.errors').innerHTML = errors.join('<br>');
            return;
        }

        upload(files[0], markAsUploaded);
    }

    function newTab() {
        var randId = rand();
        document.getElementById('custom-tabs').insertAdjacentHTML('beforeend', '<div class="new-tab mt-3"><button class="dark outline" onclick="removeTab(this)">Remove</button><input class="custom-tab-title" type="text" name="tab[title][]" placeholder="Title"><input class="custom-tab-content" id="' + randId + '" type="hidden" name="tab[body][]"><trix-editor input="' + randId + '"></trix-editor></div>');
    }

    function rand() {
        return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
    }

    function upload(openApi, cb) {
        var xhr = new XMLHttpRequest();
        var formData = new FormData();

        uploader.classList.add('uploading');

        xhr.open('POST', bladeLookup('openApiUrl'));
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.setRequestHeader('X-CSRF-TOKEN', document.getElementsByName("csrf-token")[0].content);

        formData.append("openApi", openApi);
        formData.append(
            "csrf",
            document.querySelector('[name="csrf-token"]').getAttribute("content")
        );

        xhr.send(formData);

        xhr.onload = function() {
            if (/^2/.test(xhr.status)) {
                result = xhr.responseText ? JSON.parse(xhr.responseText) : { message: "Success" };

                cb && cb(result);
            } else if (/^3/.test(xhr.status)) {
                result = xhr.responseText ? JSON.parse(xhr.responseText) : { message: "Request tried to redirect" };

                cb && cb(result);
            } else {
                result = xhr.responseText ? JSON.parse(xhr.responseText) : { message: "Unknown error" };

                cb && cb(result);
            }
        };
    }

    function markAsUploaded(response) {
        var alertType = response.success ? 'success' : 'error';

        addAlert(alertType, response.message);

        uploader.classList.remove('uploading');
    }

    function validateForm(ev) {
        var customTabTitle = document.querySelectorAll('.custom-tab-title');
        var customTabContent = document.querySelectorAll('.custom-tab-content');
        var errors = [];

        for (var i = customTabTitle.length - 1; i >= 0; i--) {
            if (
                (customTabTitle[i].value !== '' && customTabContent[i].value === '') ||
                (customTabTitle[i].value === '' && customTabContent[i].value !== '')
            ) {
                errors.push('The custom tab is mising information');
                break;
            }
        }

        if (errors.length > 0) {
            ev.preventDefault();
            return void addAlert('error', errors);
        }

        addLoading('Updating...');
    }
}());

function removeTab(el) {
    el.parentNode.remove();
}

addEventListener("trix-attachment-add", verifyUpload);

function verifyUpload(event) {
    if (!event.attachment.file) return;

    uploadFileAttachment(event.attachment);
}

function uploadFileAttachment(attachment) {
    uploadFile(attachment.file, setProgress, setAttributes);

    function setProgress(progress) {
        attachment.setUploadProgress(progress);
    }

    function setAttributes(attributes) {
        attachment.setAttributes(attributes);
    }
}

function uploadFile(file, progressCallback, successCallback) {
    var key = createStorageKey(file);
    var formData = createFormData(key, file);
    var xhr = new XMLHttpRequest();
    var host = bladeLookup('uploadImageUrl');

    xhr.open("POST", host, true);
    xhr.setRequestHeader('X-CSRF-TOKEN', document.getElementsByName("csrf-token")[0].content);

    xhr.upload.addEventListener("progress", function(event) {
        var progress = event.loaded / event.total * 100;
        progressCallback(progress);
    })

    xhr.addEventListener("load", function() {
        if (xhr.status === 201) {
            result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

            var attributes = {
                url: result.body,
                href: result.body + "?content-disposition=attachment"
            };
            successCallback(attributes);
        }
    })

    xhr.send(formData);
}

function createStorageKey(file) {
    var date = new Date();
    var day = date.toISOString().slice(0, 10);
    var name = date.getTime() + "-" + file.name;
    return [day, name].join("/");
}

function createFormData(key, file) {
    var data = new FormData();
    data.append("key", key);
    data.append("Content-Type", file.type);
    data.append("file", file);
    return data;
}
