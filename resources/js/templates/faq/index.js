var accordions = document.querySelectorAll('.accordion .title');

for (var i = 0; i < accordions.length; i++) {
    accordions[i].addEventListener('click', toggleAccordion)
}

function toggleAccordion(event) {
    var accordion = event.currentTarget;

    console.log(accordion);

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
    console.log(article);
}

var select = document.querySelector('#categories').addEventListener('change', handleSelectCategory);

function handleSelectCategory(event) {
    var form = document.querySelector('.contact-form');
    var fintech = document.getElementById('fintech');

    if (event.target.value === 'Fintech') {
        form.classList.add('hide');
        fintech.classList.add('show');
    } else {
        form.classList.remove('hide');
        fintech.classList.remove('show');
    }
}
