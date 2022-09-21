function closeDialogBox(node) {
    node = node.closest('.mdp-dialog-box');
    if(!node) return;
    node.classList.remove('show');
    node.dispatchEvent(new Event('dialog-closed'));
}