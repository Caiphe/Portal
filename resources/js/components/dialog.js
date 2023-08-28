function closeDialog(node) {
    for (var i = 0; i < 2; i++) {
        node = node.parentNode;

        if (node.classList.contains('mdp-dialog')) {
            node.classList.remove('show');
            break;
        }

        var radiosList = document.querySelectorAll('input[name="transfer-ownership-check"]');
        if(radiosList){
            radiosList.forEach(function(radioButton) {
                radioButton.checked = false;
            });
        }
    }

    node.dispatchEvent(new Event('dialog-closed'));
}