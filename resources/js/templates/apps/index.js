var headings = document.querySelectorAll('.heading-app');

for (var i = 0; i < headings.length; i++) {
    headings[i].addEventListener('click', handleHeadingClick)
}

function handleHeadingClick(event) {
    var heading = event.currentTarget;

    heading.nextElementSibling.classList.toggle('collapse');
    heading.querySelector('svg').classList.toggle('active');
}

var buttons = document.querySelectorAll('.name');

for (var j = 0; j < buttons.length; j ++) {
    buttons[j].addEventListener('click', handleButtonClick);
}

function handleButtonClick(event) {
    var parent = this.parentNode.parentNode;

    if (parent.querySelector('.detail').style.display === 'block') {
        parent.querySelector('.detail').style.display = 'none';
    } else {
        parent.querySelector('.detail').style.display = 'block';
    }
}

var actions = document.querySelectorAll('.actions');

for (var k = 0; k < actions.length; k ++) {
    actions[k].addEventListener('click', handleMenuClick);
}

function handleMenuClick() {
    var parent = this.parentNode.parentNode;

    parent.querySelector('.menu').classList.toggle('show');
    parent.querySelector('.modal').classList.toggle('show');
}

var modals = document.querySelectorAll('.modal');
for (var l = 0; l < modals.length; l ++) {
    modals[l].addEventListener('click', function() {
        document.querySelector(".modal.show").classList.remove('show');
        document.querySelector(".menu.show").classList.remove('show');
    })
}

var deleteButtons = document.querySelectorAll('.app-delete');
for (var m = 0; m < modals.length; m ++) {
    deleteButtons[m].addEventListener('click', handleDeleteMenuClick);
}
function handleDeleteMenuClick(event) {
    var app = event.currentTarget;

    var deleteApp = confirm('Are you sure you want to delete this app?');

    if(!deleteApp) {
        document.querySelector(".menu.show").classList.remove('show');
    }
}

// FIXME: COPYING KEY AND SECRET IS NOT WORKING.
var keys = document.querySelectorAll('.copy');

for (var i = 0; i < keys.length; i ++) {
    keys[i].addEventListener('click', copyText);
}

function copyText(id) {
    var el = document.getElementById(id);
    el.select();
    /* Copy the text inside the text field */
    document.execCommand("copy");
    el.blur();
}
