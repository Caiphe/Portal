function multiselectChanged(el) {
    var value = el.value;
    var tagEl = document.getElementById(el.id.replace(/select$/, 'tags'));
    var multiselectEl = document.getElementById(el.id.replace(/-select$/, ''));
    
    if(multiselectEl.options[el.selectedIndex].selected) {
        el.selectedIndex = 0;
        return;
    };

    for (var i = multiselectEl.options.length - 1; i >= 0; i--) {
        if(multiselectEl.options[i].value !== value) continue;
        multiselectEl.options[i].selected = true;
        value = multiselectEl.options[i].textContent;
        break;
    }

    tagEl.appendChild(createTag(value, el.selectedIndex, multiselectEl.id));
    el.selectedIndex = 0;
}

function createTag(value, index, id) {
    var tag = document.createElement('span');
    tag.className = 'tag removeable';
    tag.textContent = value;
    tag.dataset.index = index;
    tag.dataset.id = id;

    return tag;
}

function removeTag(ev) {
   ev.target.parentNode.removeChild(ev.target);
   document.getElementById(ev.target.dataset.id).options[ev.target.dataset.index].selected = false;
}