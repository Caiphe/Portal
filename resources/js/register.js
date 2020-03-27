var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
  // This function will display the specified tab of the form ...
  var x = document.getElementsByClassName("step__wizzard_item");
  x[n].style.display = "table-cell";
  // ... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("stepWizzardPrevBtn").style.display = "none";
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
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("step__wizzard_item");

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

function calcProgress(n, numberOfSteps) {
    var progress = document.getElementById("stepWizzardProgress");
    var step = Math.round(100 / numberOfSteps);
    progress.style.width = (n == 0) ? (step) + "%" : (step * (n + 1)) + "%";
}