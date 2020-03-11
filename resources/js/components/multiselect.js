function multiselectChanged(e) {
    var tagEl = document.getElementById(e.id + "-tags");
    tagEl.appendChild(createTag(e.value));
}

function createTag(name) {
    var tag = document.createElement('span');
    tag.className = 'tag removeable';
    tag.textContent = name;

    return tag;
}