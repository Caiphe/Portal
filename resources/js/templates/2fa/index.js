var checkConfirm = document.getElementById('confirm');
var resetTwoaf = document.getElementById('reset-twofa');
var resetBtn = document.getElementById('reset-btn');
var form2fa = document.getElementById('form-2fa');
var resetForm = document.getElementById('reset-form');
var resetContainer = document.getElementById('reset-container');
var completeTwofaContainer = document.getElementById('complete-request');

checkConfirm.addEventListener('click', toggleButton);
function toggleButton(){
    if(this.checked){
        resetBtn.classList.remove('non-active');
    }else{
        resetBtn.classList.add('non-active');
    }
}

resetTwoaf.addEventListener('click', resetTwoafForm);
function resetTwoafForm(){
    this.classList.add('hide');
    form2fa.classList.add('hide');
    resetContainer.classList.add('show');
}

resetForm.addEventListener('submit', submitResetTwofa);
function submitResetTwofa(ev){
    ev.preventDefault();
    resetContainer.classList.remove('show');
    completeTwofaContainer.classList.add('show');

}
