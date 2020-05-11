(function() {
    var timeOut = null;
    var packageCheckboxes = document.querySelectorAll(".filter-checkbox");

    document
        .getElementById("filter-text")
        .addEventListener("keyup", searchText);
    document
        .getElementById("filter-country")
        .addEventListener("change", filterProducts);
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
        var searchCountries = Array.prototype.reduce.call(
            document.getElementById("filter-country"),
            function(carry, option) {
                if (option.selected) carry.push(option.value);

                return carry;
            },
            []
        );

        var cards = document.querySelectorAll(".card--product");
        var regex = new RegExp(searchTerm, "i");
        var productCountries = [];

        for (var i = cards.length - 1; i >= 0; i--) {
            productCountries = cards[i].dataset.locations.split(",");

            if (
                (searchTerm === "" && searchCountries.length === 0) ||
                (
                    (
                        searchTerm === "" ||
                        (regex.test(cards[i].dataset.title)) ||
                        (regex.test(cards[i].dataset.group))
                    ) &&
                    (
                        searchCountries.length === 0 ||
                        searchCountries.every(function(val) {
                            return productCountries.indexOf(val) >= 0 || productCountries.indexOf('all') >= 0;
                        })
                    )
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
        for (var i = packageCheckboxes.length - 1; i >= 0; i--) {
            if (packageCheckboxes[i].checked) {
                document
                    .getElementById(
                        "bundle-title-" + packageCheckboxes[i].dataset.name
                    )
                    .classList.remove("hide");
                document
                    .getElementById(
                        "products-" + packageCheckboxes[i].dataset.name
                    )
                    .classList.remove("hide");
            } else {
                document
                    .getElementById(
                        "bundle-title-" + packageCheckboxes[i].dataset.name
                    )
                    .classList.add("hide");
                document
                    .getElementById(
                        "products-" + packageCheckboxes[i].dataset.name
                    )
                    .classList.add("hide");
            }
        }
    }
})();
