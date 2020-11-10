(function() {
    var fileUploadInput = document.querySelectorAll('.file-upload-input');
    document.getElementById('business-type').addEventListener('change', swapBusinessType);

    for (var i = fileUploadInput.length - 1; i >= 0; i--) {
        fileUploadInput[i].addEventListener('change', inputHasChanged);
    }

    function swapBusinessType() {
        var businessTypeShow = document.querySelector('.business-type.show');
        if (businessTypeShow) businessTypeShow.classList.remove('show');
        console.log(strSlug(this.value));
        document.getElementById(strSlug(this.value)).classList.add('show');
    }

    function inputHasChanged() {
        this.parentNode.classList.add('has-file');
    }

    function strSlug(str) {
        return str.replace(/[^a-zA-Z\s]/g, '').replace(/\s+/g, '-').toLowerCase();
    }
}());
