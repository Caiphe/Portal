(function(){
    document.getElementById('new-tab').addEventListener('click', newTab);

    function newTab() {
        document.getElementById('hr').insertAdjacentHTML('beforebegin', '<div class="new-tab"><input type="text" name="tab[title][]" placeholder="Title"><textarea name="tab[body][]" placeholder="Body"></textarea></div>');
    }
}());