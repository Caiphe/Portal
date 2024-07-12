init();
ajaxifyOnPopState = updateFilters;
ajaxifyComplete.push(init);
function updateFilters(params) {
    document.getElementById('search-page').value = params['q'] || '';
    document.querySelector('.team-country').value = params['country'] || '';
}

var teamMobileAction = document.querySelectorAll('.team-mobile-action');
for(var i = 0; i < teamMobileAction.length; i++) {
    teamMobileAction[i].addEventListener('click', showActions);
}

function showActions(){
    var eachTeam = this.parentElement;
    eachTeam.classList.toggle('show-action');
    console.log(eachTeam);
}
