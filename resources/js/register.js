var currentTab = 0; // Current tab is set to be the first tab (0)
var selectedLocationsCount = 0;
showTab(currentTab); // Display the current tab
var FormData = {};

function showTab(n) {
  // This function will display the specified tab of the form ...
  var x = document.getElementsByClassName("step__wizzard_item");
  x[n].style.display = "table-cell";
  // ... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("stepWizzardPrevBtn").style.display = "none";
    addKeyListner("stepWizzardForm");
  } else {
    document.getElementById("stepWizzardPrevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    document.getElementById("stepWizzardNextBtn").innerHTML = "Create new account";
  } else {
    document.getElementById("stepWizzardNextBtn").innerHTML = "Next";
  }
  // ... and run a function that displays the correct step indicator:
  calcProgress(n, x.length)
}

function nextPrev(n) {
  // removeClassFromLocations();
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("step__wizzard_item");
  if (n == 1) {
    var firstName = document.getElementById("formFirstName");
    // var email = document.getElementById("formEmail");
    // if (!checkInputNotEmpty(firstName) && !checkInputNotEmpty(email)) {
    //   return;
    // }
    setFormUserName(firstName.value);
  } else if (n == 2) {
    // var lastName = document.getElementById("formLastName");
    // if (checkInputNotEmpty(lastName))
    //   return;
  } else if (n == 3) {
    // var password = document.getElementById("formPassword");
    // var passwordConf = document.getElementById("formPasswordConf");
    // if (checkInputNotEmpty(password) && checkInputNotEmpty(passwordConf) && password == passwordConf)
    //   return;
  } else if (n == (x.length - 1)) {
    FormData.firstName = document.getElementById("formFirstName");
    FormData.lastName = document.getElementById("formFirstName");
    FormData.email = document.getElementById("formEmail");
    FormData.password = document.getElementById("formPassword");
    FormData.passwordConfirm = document.getElementById("formPasswordConf");
    FormData.locations = document.getElementById("formCountry");
    console.log(FormData);
  }

  // Hide the current tab
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form... :
  if (currentTab >= x.length) {
    console.log("made it to the end of the form!");
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function setFormUserName(name) {
  var nameSlots = document.getElementsByClassName("first__name_slot");
  for(var i = 0; i < nameSlots.length; i++) {
    nameSlots[i].innerHTML = name;
  }
}

function calcProgress(n, numberOfSteps) {
    var progress = document.getElementById("stepWizzardProgress");
    var step = Math.round(100 / numberOfSteps);
    progress.style.width = (n == 0) ? (step) + "%" : (step * (n + 1)) + "%";
}

function checkInputNotEmpty(input) {
  if (input.value == "" || input.value == null)
    return false;
  return true;
}

function addKeyListner(elemId) {
  var key = document.getElementById(elemId);
  key.addEventListener("keyup", function(event) {
    if (event.keyCode === 13) {
      event.preventDefault();
      document.getElementById("stepWizzardNextBtn").click();
    }
  });
}

function selectLocation(event) {
  if (event.target.tagName == "IMG") {
    var locations = document.getElementById("formLocationOptions");
    if (checkIfClassExists(locations, "untouched")) {
      removeUntouchedClass(locations, "untouched");
    }
    // console.log(event.target.getAttribute("data-value"));
    toggleSelectedOption(event.target, locations);
  }
  if (selectedLocationsCount == 0) {
    document.getElementById("locationsErrorText").style.display = "block";
  } else {
    document.getElementById("locationsErrorText").style.display = "none";
  }
}

function removeUntouchedClass(elem, className) {
  elem.classList.remove(className);
}

function toggleSelectedOption(elem, elemContainer) {
  if (checkIfClassExists(elem, "selected")) {
    selectedLocationsCount -= 1;
    elem.classList.remove("selected");
  } else {
    selectedLocationsCount += 1;
    elem.classList.add("selected");
  }
}

function checkIfClassExists(elem, className) {
  if (elem.classList.contains(className)) {
    return true;
  }
  return false;
}