(function() {
    var closeDialogs = document.querySelectorAll('.mdp-dialog .close-dialog');

    for (var i = closeDialogs.length - 1; i >= 0; i--) {
        closeDialogs[i].addEventListener('click', closeDialog);
    }

    function closeDialog() {
        var node = this;

        for (var i = 0; i < 2; i++) {
            node = node.parentNode;

            if (node.classList.contains('mdp-dialog')) {
                node.classList.remove('show');
                break;
            }
        }
    }
}());