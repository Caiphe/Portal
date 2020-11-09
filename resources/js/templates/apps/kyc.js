(function() {
    document.getElementById('business-type').addEventListener('change', swapBusinessType);

    function swapBusinessType() {
        var businessTypeShow = document.querySelector('.business-type.show');
        if (businessTypeShow) businessTypeShow.classList.remove('show');
        document.getElementById(this.value).classList.add('show');
    }
}());
