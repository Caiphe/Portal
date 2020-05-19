(function() {
    var timeOut = null;
    var packageCheckboxes = document.querySelectorAll(".filter-checkbox");

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
        var cards = document.querySelectorAll(".bundle-card");
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
        var bundleCard = void 0;
        for (var i = packageCheckboxes.length - 1; i >= 0; i--) {
            bundleCard = document.getElementById("bundle-" + packageCheckboxes[i].dataset.name);
            if (packageCheckboxes[i].checked) {
                bundleCard.classList.remove("hide");
            } else {
                bundleCard.classList.add("hide");
            }
        }
    }
})();
