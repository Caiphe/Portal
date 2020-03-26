function filterProducts(filterGroup) {
    var categoriesChecked = document.querySelectorAll(
        "input[name=Categories]:checked"
    );
    var groupChecked = document.querySelectorAll("input[name=Group]:checked");
    var countrySelect = getSelected(
        document.getElementById("filter-country-select")
    );
    if (filterGroup === "Group") {
        var products = document.querySelectorAll(".card--product");
        for (var i = products.length - 1; i >= 0; i--) {
            products[i].style.display = "none";
            if (
                groupChecked.length === 0 ||
                in_checked_array(groupChecked, products[i].dataset.group)
            )
                products[i].style.display = "inline-block";
        }
    } else if (filterGroup === "Categories") {
        var categories = document.querySelectorAll(".product-category");
        for (var i = categories.length - 1; i >= 0; i--) {
            categories[i].style.display = "none";
            if (
                categoriesChecked.length === 0 ||
                in_checked_array(
                    categoriesChecked,
                    categories[i].dataset.category
                )
            )
                categories[i].style.display = "block";
        }
    } else {
        console.log(countrySelect);
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
function in_checked_array(haystack, needle) {
    if (haystack.length > 0) {
        for (var i = haystack.length - 1; i >= 0; i--) {
            if (haystack[i].value === needle) return true;
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
