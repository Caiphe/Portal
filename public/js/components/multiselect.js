function multiselectChanged(el) {
    var value = el.value;
    var tagEl = document.getElementById(el.id.replace(/select$/, 'tags'));
    var multiselectEl = document.getElementById(el.id.replace(/-select$/, ''));
    console.log();
    tagEl.appendChild(createTag(value));
    el.selectedIndex = 0;

    for (var i = multiselectEl.options.length - 1; i >= 0; i--) {
        if(multiselectEl.options[i].value !== value) continue;
        multiselectEl.options[i].selected = true;
        break;
    }
}

function createTag(name) {
    var tag = document.createElement('span');
    tag.className = 'tag removeable';
    tag.textContent = name;

    return tag;
}

function removeTag(ev) {
   ev.target.parentNode.removeChild(ev.target);
}