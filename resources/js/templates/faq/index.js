var accordions = document.querySelectorAll('.accordion');

for (var i = 0; i < accordions.length; i++) {
    accordions[i].addEventListener('click', toggleAccordion)
}

function toggleAccordion(event) {
    var accordion = event.currentTarget;

    accordion.querySelector('svg').classList.toggle('active');
    accordion.querySelector('article').classList.toggle('expand');

    console.log(accordion)
}

// var accordionContents = document.querySelectorAll('.content');
//
// for (var j = 0; j < accordionContents.length; j++) {
//     accordionContents[j].addEventListener('click', toggleAccordionContent)
// }
//
// function toggleAccordionContent(event) {
//     console.log(event.currentTarget);
// }
