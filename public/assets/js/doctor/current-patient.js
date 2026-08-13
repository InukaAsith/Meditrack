const consultSubtabGroups = document.querySelectorAll("[data-subtabs]");

for (const group of consultSubtabGroups) {
  group.addEventListener("click", (event) => {
    const tab = event.target.closest("[data-subtab]");
    if (!tab || !group.contains(tab)) return;

    const key = tab.getAttribute("data-subtab");
    for (const otherTab of group.querySelectorAll("[data-subtab]")) {
      otherTab.classList.toggle("is-active", otherTab === tab);
    }
    for (const panel of group.querySelectorAll("[data-subpanel]")) {
      panel.hidden = panel.getAttribute("data-subpanel") !== key;
    }
  });
}

const consultTimerDisplay = document.getElementById("timer-display");
const consultTimer = document.getElementById("consult-timer");
let consultSeconds = 7 * 60 + 12;

if (consultTimerDisplay) {
  setInterval(tickConsultTimer, 1000);
}

function tickConsultTimer() {
  consultSeconds++;
  const minutes = Math.floor(consultSeconds / 60);
  const seconds = consultSeconds % 60;
  consultTimerDisplay.textContent =
    String(minutes).padStart(2, "0") + ":" + String(seconds).padStart(2, "0");

  if (minutes >= 15 && consultTimer) {
    consultTimer.classList.add("consultation-current-card__timer--over");
  }
}

const addRxButton = document.getElementById("add-rx-line");
const rxLineList = document.getElementById("rx-lines");

if (addRxButton && rxLineList) {
  addRxButton.addEventListener("click", () => {
    const line = document.createElement("div");
    line.className = "consultation-rx-line";
    line.innerHTML =
      '<input type="text" placeholder="Medicine name">' +
      '<input type="text" placeholder="Dose">' +
      "<select><option>1× daily</option><option>2× daily</option><option>3× daily</option><option>PRN</option></select>" +
      '<input type="number" placeholder="Days" style="width:60px">' +
      '<input type="number" placeholder="Qty" style="width:60px">' +
      '<button class="consultation-rx-line__remove" type="button" title="Remove">✕</button>';
    rxLineList.appendChild(line);
    line.querySelector("input").focus();
  });

  rxLineList.addEventListener("click", (event) => {
    const removeButton = event.target.closest(".consultation-rx-line__remove");
    if (!removeButton) return;

    removeButton.closest(".consultation-rx-line").remove();
  });
}

const addDiagnosisButton = document.getElementById("add-diagnosis");
const diagnosisList = document.getElementById("diagnosis-list");

if (addDiagnosisButton && diagnosisList) {
  addDiagnosisButton.addEventListener("click", () => {
    const row = document.createElement("div");
    row.className = "consultation-diagnosis__row";
    row.innerHTML =
      '<input class="consultation-diagnosis__code" type="text" placeholder="Code">' +
      '<input class="consultation-diagnosis__desc" type="text" placeholder="Description">';
    diagnosisList.appendChild(row);
    row.querySelector("input").focus();
  });
}
