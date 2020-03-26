function switchSection(section) {
    document.getElementById('product-sections').className = section;
    window.location.hash = '/' + section.replace('product-', '');
}

(function() {
    hash = window.location.hash.replace(/#\/?/, '');

    if (hash !== '') {
        document.getElementById('button-' + hash).click();
    }
}());
