var accordions = document.querySelectorAll('.accordion .title');

for (var i = 0; i < accordions.length; i++) {
    accordions[i].addEventListener('click', toggleAccordion)
}

function toggleAccordion(event) {
    var accordion = event.currentTarget;
    var children = accordion.parentNode.children;

    for (var i = 1; i < children.length; i++) {
        children[i].classList.toggle('active');
        children[i].classList.toggle('expand');
    }
}

var accordionContents = document.querySelectorAll('article header');

for (var j = 0; j < accordionContents.length; j++) {
    accordionContents[j].addEventListener('click', toggleAccordionContent)
}

function toggleAccordionContent(event) {
    var article = event.currentTarget;

    article.nextElementSibling.classList.toggle('expand');

    if(article.nextElementSibling.classList.contains('expand')) {
        article.classList.add('bottom');
    } else {
        article.classList.remove('bottom');
    }

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
    var questions = document.querySelectorAll('article').innerHTML;
    var match = new RegExp(filter, "gi");

    var faqDict = {
        'faq-1': [
            'category',
            'Is the API down?',
            'If you need verify if the MTN API Platform is up and responsive, or perhaps down due to maintenance, then check out the status page'
        ],
        'faq-2': [
            'test',
            'testing',
            'test test'
        ],
        // 'faq-3': [
        //
        // ],
        // 'faq-4': [
        //
        // ],
        // 'faq-5': [
        //
        // ],
        // 'faq-6': [
        //
        // ],
        // 'faq-7': [
        //
        // ],
        // 'faq-8': [
        //
        // ],
        // 'faq-9': [
        //
        // ],
        // 'faq-10': [
        //
        // ],
        // 'faq-11': [
        //
        // ]
    }
    var found = [];

    var entries = Object.entries(faqDict);

    for (var i = 0; i < entries.length; i++) {

        var sub = entries[i];

        var item = sub[1].filter(function(item) {
            sentenceCase(item.toLowerCase());
            return item.match(filter) || item.toLowerCase().match(match);
        });

        found.push(item);
    }

    for (var j = 0; j < categories.length; j++) {
        categories[j].style.display = "none";

        textValid = filter === "" || categories[j].dataset.category.match(match) || inArray(found, filter);

        if (textValid) categories[j].style.display = "flex";

        categories[j].querySelector('svg').classList.add('active');
        categories[j].querySelector('article').classList.add('expand');

        if (filter === "") {
            categories[j].querySelector('svg').classList.remove('active');
            categories[j].querySelector('article').classList.remove('expand');
        }
    }
}

function sentenceCase(str) {
    return str.replace(/[a-z]/i, function (letter) {
        return letter.toUpperCase();
    }).trim();
}

function inArray(haystack, needle) {
    for (var i = 0; i < haystack.length; i++) {
        return !!haystack[i].indexOf(needle);
    }
}
