(function() {
    var timeOut = null;
    var packageCheckboxes = document.querySelectorAll('.filter-checkbox');

    document.getElementById('filter-text').addEventListener('keyup', searchText);
    for (var i = packageCheckboxes.length - 1; i >= 0; i--) {
        packageCheckboxes[i].addEventListener('change', filterPackages);
    }

    function searchText(ev) {
        if (timeOut !== null) {
            clearTimeout(timeOut);
            timeOut = null;
        }

        timeOut = setTimeout(filterProducts.bind(null, ev.target.value), 512);
    }

    function filterProducts(searchTerm) {
        var cards = document.querySelectorAll('.card--product');
        var regex = new RegExp(searchTerm, 'i');

        for (var i = cards.length - 1; i >= 0; i--) {
            if(
                regex.test(cards[i].dataset.title) ||
                regex.test(cards[i].dataset.group)
            ){
                cards[i].classList.remove('hide');
            } else {
                cards[i].classList.add('hide');
            }
        }

        timeOut = null;
    }

    function filterPackages() {
        for (var i = packageCheckboxes.length - 1; i >= 0; i--) {
            if(packageCheckboxes[i].checked){
                document.getElementById('bundle-title-' + packageCheckboxes[i].dataset.name).classList.remove('hide');
                document.getElementById('products-' + packageCheckboxes[i].dataset.name).classList.remove('hide');
            } else {
                document.getElementById('bundle-title-' + packageCheckboxes[i].dataset.name).classList.add('hide');
                document.getElementById('products-' + packageCheckboxes[i].dataset.name).classList.add('hide');
            }
        }
    }
}());
