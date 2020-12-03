(function() {
    var fileUploadInput = document.querySelectorAll('.file-upload-input');
    document.getElementById('business-type').addEventListener('change', swapBusinessType);
    document.getElementById('kyc-form').addEventListener('submit', validateForm);

    for (var i = fileUploadInput.length - 1; i >= 0; i--) {
        fileUploadInput[i].addEventListener('change', inputHasChanged);
    }

    function swapBusinessType() {
        var businessTypeShow = document.querySelector('.business-type.show');
        if (businessTypeShow) businessTypeShow.classList.remove('show');
        document.getElementById(strSlug(this.value)).classList.add('show');
    }

    function inputHasChanged() {
        var currentChoosenFile = this.parentNode.nextElementSibling;
        this.parentNode.classList.add('has-file');

        if (currentChoosenFile && currentChoosenFile.className === 'chosen-file') {
            currentChoosenFile.parentNode.removeChild(currentChoosenFile);
        }

        this.parentNode.insertAdjacentHTML('afterend', '<span class="chosen-file" onclick="removeUpload(this)"><button type="button">&times;</button>' + this.value.replace(/.*[\\\/]/, '') + '</span>')
    }

    function validateForm(e) {
        var files = document.querySelectorAll('.business-type.show input[type="file"]');
        var signedContractingRequirements = document.getElementById('signed-contracting-requirements');

        if (signedContractingRequirements.value === "") {
            e.preventDefault();
            return void addAlert('error', 'Please add all the files requested.');
        }

        for (var i = files.length - 1; i >= 0; i--) {
            if (files[i].value === "") {
                e.preventDefault();
                return void addAlert('error', 'Please add all the files requested.');
            }
        }
    }

    function strSlug(str) {
        return str.replace(/[^a-zA-Z\s]/g, '').replace(/\s+/g, '-').toLowerCase();
    }
}());

function removeUpload(el) {
    var fileLabel = el.previousElementSibling;
    fileLabel.classList.remove('has-file');
    fileLabel.querySelector('.file-upload-input').value = "";

    el.parentNode.removeChild(el);
}
