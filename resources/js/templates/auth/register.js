var currentTab = 0; // Current tab is set to be the first tab (0)
var selectedLocationsCount = 0;
showTab(currentTab); // Display the current tab
var FormData = {};

document.getElementById("termsSwitch").addEventListener("change", toggleAgree);

function showTab(n) {
    // This function will display the specified tab of the form ...
    var x = document.getElementsByClassName("step__wizard_item");
    x[n].style.display = "block";
    // ... and fix the Previous/Next buttons:
    if (n == 0) {
        document.getElementById("stepwizardPrevBtn").style.display = "none";
        addKeyListner("stepwizardForm");
    } else {
        document.getElementById("stepwizardPrevBtn").style.display = "inline";
    }
    if (n == x.length - 1) {
        document.getElementById("stepWizardSubmitBtn").style.display = "inline";
        document.getElementById("stepwizardNextBtn").style.display = "none";
    } else {
        document.getElementById("stepwizardNextBtn").innerHTML = "Next";
    }
    // ... and run a function that displays the correct step indicator:
    calcProgress(n, x.length);
}

function readyToSubmit(event) {
    var stepwizardForm = document.getElementById("stepwizardForm");
    stepwizardForm.submit();
}

function nextPrev(n) {
    // This function will figure out which tab to display
    var x = document.getElementsByClassName("step__wizard_item");

    // User clicked on the next button
    if (n == 1) {
        if (currentTab == 0) {
            var firstName = document.getElementById("formFirstName");
            var email = document.getElementById("formEmail");
            if (checkInputNotEmpty(firstName)) {
                setFormUserName(firstName.value);
            }
            if (!checkInputNotEmpty(firstName) || !checkInputNotEmpty(email)) {
                document.getElementById("FormStepErrorMsg").style.display =
                    "block";
                document.getElementById(
                    "FormStepErrorMsg"
                ).children[0].innerHTML = "Please fill in all fields in Step!";
                return;
            }
        } else if (currentTab == 1) {
            var lastName = document.getElementById("formLastName");
            if (!checkInputNotEmpty(lastName)) {
                document.getElementById("FormStepErrorMsg").style.display =
                    "block";
                document.getElementById(
                    "FormStepErrorMsg"
                ).children[0].innerHTML = "Please fill in all fields in Step!";
                return;
            }
        } else if (currentTab == 2) {
            var password = document.getElementById("formPassword");
            var passwordConf = document.getElementById("formPasswordConf");
            if (
                !checkInputNotEmpty(password) ||
                !checkInputNotEmpty(passwordConf)
            ) {
                document.getElementById("FormStepErrorMsg").style.display =
                    "block";
                document.getElementById(
                    "FormStepErrorMsg"
                ).children[0].innerHTML = "Please fill in all fields in Step!";
                return;
            }
            if (password.value !== passwordConf.value) {
                document.getElementById("FormStepErrorMsg").style.display =
                    "block";
                document.getElementById(
                    "FormStepErrorMsg"
                ).children[0].innerHTML = "Passwords don't match";
                return;
            }
        }
    }

    // User clicked on the back button
    if (n == -1) {
        if (currentTab == 4) {
            document.getElementById("stepwizardNextBtn").style.display =
                "inline";
            document.getElementById("stepWizardSubmitBtn").style.display =
                "none";
        }
    }

    // Remove the error message box from view if it was set previously
    document.getElementById("FormStepErrorMsg").style.display = "none";

    // Hide the current tab
    x[currentTab].style.display = "none";
    // Increase or decrease the current tab by 1:
    currentTab = currentTab + n;
    // if you have reached the end of the form... :
    if (currentTab >= x.length) {
        return false;
    }
    // Otherwise, display the correct tab:
    showTab(currentTab);
}

function setFormUserName(name) {
    var nameSlots = document.getElementsByClassName("first__name_slot");
    for (var i = 0; i < nameSlots.length; i++) {
        nameSlots[i].innerHTML = name;
    }
}

function calcProgress(n, numberOfSteps) {
    var progress = document.getElementById("stepwizardProgress");
    var step = Math.round(100 / numberOfSteps);
    progress.style.width = n == 0 ? step + "%" : step * (n + 1) + "%";
}

function checkInputNotEmpty(input) {
    if (input.value == "" || input.value == null) return false;
    return true;
}

function addKeyListner(elemId) {
    var key = document.getElementById(elemId);
    key.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            document.getElementById("stepwizardNextBtn").click();
        }
    });
}

function selectLocation(event) {
    if (event.target.tagName == "IMG") {
        var locations = document.getElementById("formLocationOptions");
        if (checkIfClassExists(locations, "untouched")) {
            removeUntouchedClass(locations, "untouched");
        }
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

function checkPasswordStrength(event) {
    var password = event.target.value;
    var passwordStrengthBtn = document.getElementById("passwordStrengthBtn");
    var passwordStrengthArray = ["grey", "yellow", "good"];
    // Do not show anything when the length of password is zero.
    if (password.length === 0) {
        return;
    }
    // Create an array and push all possible values that you want in password
    var matchedCase = new Array();
    matchedCase.push("[$@$!%*#?&]");
    matchedCase.push("[A-Z]");
    matchedCase.push("[0-9]");
    matchedCase.push("[a-z]");

    // Check the conditions
    var counditionsPassed = 0;
    if (password.length >= 12) {
        counditionsPassed += 1;
        for (var i = 0; i < matchedCase.length; i++) {
            if (new RegExp(matchedCase[i]).test(password)) {
                counditionsPassed += 1;
            }
        }
    }

    // Display it
    var className = "";
    var strength = "";
    switch (counditionsPassed) {
        case 0:
        case 1:
        case 2:
            strength = "Very Weak";
            className = "grey";
            break;
        case 3:
            strength = "Medium Password";
            className = "grey_dark";
            break;
        case 4:
            strength = "Strong Password";
            className = "yellow";
            break;
    }
    if (!passwordStrengthBtn.classList.contains(className)) {
        for (var i = 0; i < passwordStrengthArray.length; i++) {
            if (
                checkIfClassExists(
                    passwordStrengthBtn,
                    passwordStrengthArray[i]
                )
            ) {
                removeUntouchedClass(
                    passwordStrengthBtn,
                    passwordStrengthArray[i]
                );
                i = passwordStrengthArray.length;
            }
        }
        passwordStrengthBtn.classList.add(className);
        passwordStrengthBtn.innerHTML = strength;
    }
}

function togglePasswordVisibility(that) {
    that.parentNode.classList.toggle("password-visible");
    that.previousElementSibling.setAttribute(
        "type",
        that.parentNode.classList.contains("password-visible")
            ? "text"
            : "password"
    );
}

function toggleAgree() {
    document.getElementById("stepWizardSubmitBtn").disabled = !this.checked;
}
