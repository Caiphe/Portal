(function(){
    var shownCookiePolicy = document.cookie.indexOf('shownCookiePolicy') === -1;
    var cookiePolicyOptionHeader = document.querySelectorAll('.cookie-policy-option-header');
    var cookiePolicy = null;

    if(!shownCookiePolicy) return;

    cookiePolicy = document.getElementById('cookie-policy');
    cookiePolicy.className = 'show';
    document.getElementById('cookie-policy-accept-button').addEventListener('click', agreeToCookiePolicy);
    document.getElementById('cookie-policy-preferences-button').addEventListener('click', togglePreferences);
    document.getElementById('cookie-policy-accept').addEventListener('click', agreeToCookiePolicy);

    for (var i = cookiePolicyOptionHeader.length - 1; i >= 0; i--) {
        cookiePolicyOptionHeader[i].addEventListener('click', openPolicyHeader);
    }

    function agreeToCookiePolicy() {
        var agreeType = document.getElementById('cookie-policy-analytics-switch').checked ? 'all' : 'necessary';
        document.cookie = "shownCookiePolicy=" + agreeType + ";path=/"

        togglePreferences();
        cookiePolicy.className = '';
    }

    function togglePreferences() {
        document.getElementById('cookie-policy-preferences').classList.toggle('show');
    }

    function openPolicyHeader() {
        this.parentNode.classList.toggle('open');
    }
}());