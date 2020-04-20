var accordions = document.querySelectorAll('.accordion .title');

for (var i = 0; i < accordions.length; i++) {
    accordions[i].addEventListener('click', toggleAccordion)
}

function toggleAccordion(event) {
    var accordion = event.currentTarget;
    var active = document.querySelectorAll('.active');
    console.log(active);

    // if (active !== this) {
    //     active.classList.remove("active");
    // }

    accordion.querySelector('svg').classList.toggle('active');
    accordion.nextElementSibling.classList.toggle('expand');
}

var accordionContents = document.querySelectorAll('article header');

for (var j = 0; j < accordionContents.length; j++) {
    accordionContents[j].addEventListener('click', toggleAccordionContent)
}

function toggleAccordionContent(event) {
    var article = event.currentTarget;

    article.nextElementSibling.classList.toggle('expand');
    article.querySelector('button').classList.toggle('plus');
    article.querySelector('button').classList.toggle('minus');
}

var select = document.getElementById('categories');
var value = document.getElementById('categories').value;

select.value = 'Advertising';
select.addEventListener('change', handleSelectCategory);

function handleSelectCategory(event) {
    var form = document.querySelector('.contact-form');
    var fintech = document.getElementById('fintech');

    for(var i = 0, j = select.options.length; i < j; ++i) {
        if(select.options[i].innerHTML === value) {
            select.selectedIndex = i;
            break;
        }
    }

    if (event.target.value === 'Fintech') {
        form.classList.add('hide');
        fintech.classList.add('show');
    } else {
        form.classList.remove('hide');
        fintech.classList.remove('show');
    }
}

var country = document.getElementById('countries');
country.options.selectedIndex = 0;

country.addEventListener('change', handleSelectCountry);

function handleSelectCountry(event) {
    var selected = event.currentTarget;
    var selectedCountry = selected.options[selected.selectedIndex];

    document.querySelector('.connect').style.display = 'block';
    document.querySelector('.skype').href = selectedCountry.dataset.skype;
    document.querySelector('.whatsapp').href = selectedCountry.dataset.whatsapp;
}

document.getElementById("filter-categories").addEventListener("keyup", filterCategories);

function filterCategories() {
    var categories = document.querySelectorAll(".accordion");
    var filter = document.getElementById("filter-categories").value;
    var match = new RegExp(filter, "gi");

    for (var i = categories.length - 1; i >= 0; i--) {
        categories[i].style.display = "none";

        textValid = filter === "" || categories[i].dataset.category.match(match);

        if (textValid) categories[i].style.display = "flex";

        categories[i].querySelector('svg').classList.add('active');
        categories[i].querySelector('article').classList.add('expand');

        if (filter === "") {
            categories[i].querySelector('svg').classList.remove('active');
            categories[i].querySelector('article').classList.remove('expand');
        }
    }
}
