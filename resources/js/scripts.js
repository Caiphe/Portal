document.addEventListener('DOMContentLoaded', (event) => {
    var pre = document.querySelectorAll('pre');
    hljs.configure({
        languages: ['JSON', 'JavaScript']
    });
    for (var i = pre.length - 1; i >= 0; i--) {
        hljs.highlightBlock(pre[i]);
    }
});
