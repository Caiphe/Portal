(function () {
    var uploader = document.getElementById('uploader');
    var newTabClone = document.querySelector('.new-tab').cloneNode(true);
    var addCustomTab = document.getElementById('add-custom-tab');

    document.getElementById('uploader-input').addEventListener('change', chooseSwagger);
    document.getElementById('admin-form').addEventListener('submit', validateForm);
    addCustomTab.addEventListener('click', newTab);

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

        if (files.length > 1) {
            errors.push("You can only add one file.");
        }

        if (!/\.yml$|\.yaml$/.test(files[0].name)) {
            errors.push("The file isn't the correct type.");
        }

        if (errors.length > 0) {
            return void addAlert(errors);
        }

        upload(files[0], markAsUploaded);
    }

    function newTab() {
        var randId = rand();
        var newTabNode = newTabClone.cloneNode(true);
        var newTabNodeEditor = newTabNode.querySelector('.editor');

        newTabNodeEditor.dataset.input = randId;
        addCustomTab.insertAdjacentElement('beforebegin', newTabNode);

        makeEditor(newTabNodeEditor);
    }

    function rand() {
        return 'q' + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
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

        xhr.onload = function () {
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

        addContents();

        for (var i = customTabTitle.length - 1; i >= 0; i--) {
            if (
                (customTabTitle[i].value !== '' && customTabContent[i].value === '') ||
                (customTabTitle[i].value === '' && customTabContent[i].value !== '')
            ) {
                errors.push('A custom tab is missing information');
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