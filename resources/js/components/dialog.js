function closeDialog(node) {
    for (var i = 0; i < 2; i++) {
        node = node.parentNode;

        if (node.classList.contains('mdp-dialog')) {
            node.classList.remove('show');
            break;
        }
    }

    node.dispatchEvent(new Event('dialog-closed'));
}