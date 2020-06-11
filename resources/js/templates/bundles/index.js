(function() {
    var timeOut = null;
    var packageCheckboxes = document.querySelectorAll(".filter-checkbox");
    var cards = document.querySelectorAll(".bundle-card");

    document
        .getElementById("filter-text")
        .addEventListener("keyup", searchText);
    for (var i = packageCheckboxes.length - 1; i >= 0; i--) {
        packageCheckboxes[i].addEventListener("change", filterPackages);
    }

    function searchText(ev) {
        if (timeOut !== null) {
            clearTimeout(timeOut);
            timeOut = null;
        }

        timeOut = setTimeout(filterProducts, 512);
    }

    function filterProducts() {
        var searchTerm = document.getElementById("filter-text").value;
        var regex = new RegExp(searchTerm, "i");

        for (var i = cards.length - 1; i >= 0; i--) {
            if (
                searchTerm === "" ||
                (
                    (regex.test(cards[i].dataset.title)) ||
                    (regex.test(cards[i].dataset.description))
                )
            ) {
                cards[i].classList.remove("hide");
            } else {
                cards[i].classList.add("hide");
            }
        }

        timeOut = null;
    }

    function filterPackages() {
        var chosenCategories = document.querySelectorAll('.filter-checkbox:checked');
        chosenCategories = Array.prototype.slice.call(chosenCategories).map(function(item){
            return item.value;
        });

        for (var i = cards.length - 1; i >= 0; i--) {
            if (chosenCategories.indexOf(cards[i].dataset.category) !== -1) {
                cards[i].classList.remove("hide");
            } else {
                cards[i].classList.add("hide");
            }
        }
    }
})();
