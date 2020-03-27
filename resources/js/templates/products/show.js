(function() {
    hash = window.location.hash.replace(/#\/?/, '');

    if (hash !== '') {
        document.getElementById('button-' + hash).click();
    }
}());

function switchSection(section) {
    document.getElementById('product-sections').className = section;
    window.location.hash = '/' + section.replace('product-', '');
}

function toggleParent(that) {
    that.parentNode.classList.toggle('open');
}

function toggleParameters(that) {
    that.classList.toggle('open');
}