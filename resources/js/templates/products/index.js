document
    .getElementById("filter-country-select")
    .addEventListener("change", filterCountries);
function filterCountries(){
	var countrySelect = getSelected(document.getElementById("filter-country"));
	var products = document.querySelectorAll(".card--product");
    for (var i = products.length - 1; i >= 0; i--) {
		products[i].style.display = "none";
		if (products[i].dataset.locations !== null)
            locations = products[i].dataset.locations.split(",");
        if (countrySelect.length === 0 || arrayCompare(locations, countrySelect))
            products[i].style.display = "inline-block";
    }
}
function filterProducts(filterGroup) {
    var categoriesChecked = document.querySelectorAll(
        "input[name=Categories]:checked"
    );
    var groupChecked = document.querySelectorAll("input[name=Group]:checked");
    var countrySelect = getSelected(
        document.getElementById("filter-country")
    );
    if (filterGroup === "Group") {
        var products = document.querySelectorAll(".card--product");
        for (var i = products.length - 1; i >= 0; i--) {
			products[i].style.display = "none";
			if (
                groupChecked.length === 0 ||
                inCheckedArray(groupChecked, products[i].dataset.group)
            )
                products[i].style.display = "inline-block";
        }
    } else if (filterGroup === "Categories") {
        var categories = document.querySelectorAll(".product-category");
        for (var i = categories.length - 1; i >= 0; i--) {
            categories[i].style.display = "none";
            if (
                categoriesChecked.length === 0 ||
                inCheckedArray(
                    categoriesChecked,
                    categories[i].dataset.category
                )
            )
                categories[i].style.display = "block";
        }
    } 
    if (
        groupChecked.length !== 0 ||
        categoriesChecked.length !== 0 ||
        countrySelect.length !== 0
    )
        document.getElementById("clearFilter").style.display = "block";
    else if (
        groupChecked.length === 0 &&
        categoriesChecked.length === 0 &&
        countrySelect.length === 0
    )
        document.getElementById("clearFilter").style.display = "none";
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

function clearFilter() {
    var categoriesChecked = document.querySelectorAll(
        "input[name=Categories]:checked"
    );
    if (categoriesChecked.length > 0) {
        uncheckArray(categoriesChecked);
        var categories = document.querySelectorAll(".product-category");
        for (var i = categories.length - 1; i >= 0; i--) {
            categories[i].style.display = "block";
        }
    }

    var groupChecked = document.querySelectorAll("input[name=Group]:checked");
    if (groupChecked.length > 0) {
        uncheckArray(groupChecked);
        var products = document.querySelectorAll(".card--product");
        for (var i = products.length - 1; i >= 0; i--) {
            products[i].style.display = "inline-block";
        }
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

function arrayCompare(a, b) {
    var matches = [];
    for (var i = 0; i < a.length; i++) {
        for (var e = 0; e < b.length; e++) {
            if (a[i] === b[e]) matches.push(a[i]);
        }
    }
    return (matches.length > 0);
}
