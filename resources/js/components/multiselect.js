var event = new Event('change');

function multiselectChanged(el) {
    var value = el.value;
    var multiselectEl = document.getElementById(el.id.replace(/-select$/, ''));

    if (multiselectEl.options[el.selectedIndex].selected) {
        el.selectedIndex = 0;
        return;
    };

    addTag(value, el);

    el.selectedIndex = 0;

    multiselectEl.dispatchEvent(event);
}

function addTag(value, el){
    var tagEl = document.getElementById(el.id.replace(/select$/, 'tags'));
    var multiselectEl = document.getElementById(el.id.replace(/-select$/, ''));

    for (var i = multiselectEl.options.length - 1; i >= 0; i--) {
        if (multiselectEl.options[i].value !== value) continue;
        multiselectEl.options[i].selected = true;
        value = multiselectEl.options[i].textContent;
        break;
    }

    tagEl.appendChild(createTag(value, el.selectedIndex, multiselectEl.id));

}

function createTag(value, index, id) {
    var tag = document.createElement('span');
    tag.className = 'tag grey hoverable removeable';
    tag.textContent = value;
    tag.dataset.index = index;
    tag.dataset.id = id;

    return tag;
}

function removeTag(ev) {
    var multiselectEl = document.getElementById(ev.target.dataset.id);
    ev.target.parentNode.removeChild(ev.target);
    multiselectEl.options[ev.target.dataset.index].selected = false;
    multiselectEl.dispatchEvent(event);
}
