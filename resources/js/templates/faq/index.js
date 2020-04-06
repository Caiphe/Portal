var questions = document.getElementsByClassName("question");
for (var i = 0; i < questions.length; i++) {
    questions[i].addEventListener("click", toggleFaq);
}

if (window.location.hash !== undefined) {
    document.getElementById(window.location.hash.replace(/#\/?/, '')).querySelector('.question').click();
}

function toggleFaq() {
    this.classList.toggle("active");
    
    var answer = this.nextElementSibling;
    if (answer.style.maxHeight) {
        answer.style.maxHeight = null;
    } else {
        answer.style.maxHeight = answer.scrollHeight + "px";
    }
}