function closeAlert(){document.getElementById("alert").classList.remove("open"),setTimeout(function(){document.body.removeChild(document.getElementById("alert"))},600)}function addAlert(e,t){var n,l="";if("string"==typeof t)l="<li>"+t+"</li>";else for(var o=0;o<t.length;o++)l+="<li>"+t[o]+"</li>";n='<div id="alert" class="'+e+'"><div class="container"><ul>'+l+'</ul><button class="fab blue close" onclick="closeAlert()"></button></div></div>',document.getElementById("header").insertAdjacentHTML("afterend",n),setTimeout(function(){document.getElementById("alert").classList.add("open"),setTimeout(closeAlert,2e3)},10)}document.addEventListener("DOMContentLoaded",function(){hljs.configure({languages:["JSON","JavaScript"]}),hljs.initHighlightingOnLoad()}),document.getElementById("alert")&&setTimeout(closeAlert,2e3);
