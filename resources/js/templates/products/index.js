//eventlisteners for the multiselect and filter text search
document
    .getElementById("filter-country-select")
    .addEventListener("change", filterProducts);
document
    .getElementById("filter-country-tags")
    .addEventListener("click", filterProducts);
document
    .getElementById("filter-text")
    .addEventListener("keyup", filterProducts);

//Filters products - if category hides the category row and displays categories that are checked. Otherwise it checks the filter of the textsearch, countries and group and if all are valid will display product
function filterProducts(filterGroup) {
    if (filterGroup === "Categories") {
        var categoriesChecked = document.querySelectorAll(
            "input[name=Categories]:checked"
        );
        var categories = document.querySelectorAll(".category");
        for (var i = categories.length - 1; i >= 0; i--) {
            categories[i].style.display = "none";
            if (
                categoriesChecked.length === 0 ||
                inCheckedArray(
                    categoriesChecked,
                    categories[i].dataset.category
                )
            )
                categories[i].style.display = "flex";
        }
    } else {
        var groupChecked = document.querySelectorAll(
            "input[name=Group]:checked"
        );
        var filterText = document.getElementById("filter-text").value;
        var match = new RegExp(filterText, "gi");
        var countrySelect = getSelected(
            document.getElementById("filter-country")
		);
		var products = document.querySelectorAll(".card--product");
        for (var i = products.length - 1; i >= 0; i--) {
            products[i].style.display = "none";

            groupValid =
                groupChecked.length === 0 ||
                inCheckedArray(groupChecked, products[i].dataset.group);

            textValid =
                filterText === "" || products[i].dataset.title.match(match);

			var locations =
                products[i].dataset.locations !== undefined
                    ? products[i].dataset.locations.split(",")
					: ["all"];
			countriesValid =
                countrySelect.length === 0 ||
                locations[0] === "all" ||
                arrayCompare(locations, countrySelect);

            if (groupValid && textValid && countriesValid)
                products[i].style.display = "inline-block";
        }
    }
    toggleFilter();
}

//check if a value is in a checkbox checked array
function inCheckedArray(haystack, needle) {
    if (haystack.length > 0) {
        for (var i = haystack.length - 1; i >= 0; i--) {
            if (haystack[i].value.toLowerCase() === needle.toLowerCase())
                return true;
        }
    } else {
        return false;
    }
}

//toggles filter button visibility
function toggleFilter() {
    var categoriesChecked = document.querySelectorAll(
        "input[name=Categories]:checked"
    );
    var groupChecked = document.querySelectorAll("input[name=Group]:checked");
    var countrySelect = getSelected(document.getElementById("filter-country"));
    var filterText = document.getElementById("filter-text").value;
    if (
        groupChecked.length !== 0 ||
        categoriesChecked.length !== 0 ||
        countrySelect.length !== 0 ||
        filterText.length !== 0
    )
        document.getElementById("clearFilter").style.display = "block";
    else if (
        groupChecked.length === 0 &&
            categoriesChecked.length === 0 &&
            countrySelect.length === 0 ||
        filterText.length === 0
    )
        document.getElementById("clearFilter").style.display = "none";
}

//clears filter
function clearFilter() {
    var categoriesChecked = document.querySelectorAll(
        "input[name=Categories]:checked"
    );
    if (categoriesChecked.length > 0) {
        uncheckArray(categoriesChecked);
    }

    var groupChecked = document.querySelectorAll("input[name=Group]:checked");
    if (groupChecked.length > 0) {
        uncheckArray(groupChecked);
    }
    var countrySelect = getSelected(document.getElementById("filter-country"));
    if (countrySelect.length > 0) {
		clearSelected(document.getElementById("filter-country"));
        var multiselectTags = document.getElementById("filter-country-tags");
        while (multiselectTags.firstChild) {
            multiselectTags.removeChild(multiselectTags.firstChild);
        }
	}
	document.getElementById("filter-text").value = "";

    var categories = document.querySelectorAll(".category");
    for (var i = categories.length - 1; i >= 0; i--) {
        categories[i].style.display = "flex";
    }
    var products = document.querySelectorAll(".card--product");
    for (var i = products.length - 1; i >= 0; i--) {
        products[i].style.display = "inline-block";
    }

    document.getElementById("clearFilter").style.display = "none";
}

function uncheckArray(checkArray) {
    for (var i = checkArray.length - 1; i >= 0; i--) {
        checkArray[i].checked = false;
    }
}

function getSelected(multiSelect) {
    var selected = [];
    for (var option of multiSelect.options) {
        if (option.selected) {
            selected.push(option.value);
        }
    }
    return selected;
}

function clearSelected(multiselect) {
	var elements = multiselect.options;
    for (var i = 0; i < elements.length; i++) {
        elements[i].selected = false;
	}
}

function arrayCompare(a, b) {
    var matches = [];
    for (var i = 0; i < a.length; i++) {
        for (var e = 0; e < b.length; e++) {
            if (a[i] === b[e]) matches.push(a[i]);
        }
    }
    return matches.length > 0;
}
