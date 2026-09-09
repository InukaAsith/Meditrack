let queueDoctor = "AS";

document.addEventListener("supporting:doctor", (event) => {
  queueDoctor = event.detail.code;
  const showAll = queueDoctor === "all";

  const singleDoctorView = document.querySelector('[data-lq-mode="single"]');
  const allDoctorsBoard = document.querySelector('[data-lq-mode="all"]');
  if (singleDoctorView) singleDoctorView.hidden = showAll;
  if (allDoctorsBoard) allDoctorsBoard.hidden = !showAll;

  document.querySelectorAll("[data-est-panel]").forEach((panel) => {
    panel.hidden = panel.getAttribute("data-est-panel") !== queueDoctor;
  });
  if (!showAll) {
    document.querySelectorAll("[data-queue-panel]").forEach((panel) => {
      panel.hidden = panel.getAttribute("data-queue-panel") !== queueDoctor;
    });
  }

  const boardDoctorName = document.querySelector("[data-single-doc]");
  if (boardDoctorName) boardDoctorName.textContent = event.detail.name;
  filterQueueMessageBoard();
});

function renumberList(list) {
  let number = 0;
  list.querySelectorAll(".supporting-queue-row").forEach((row) => {
    number++;
    const cell = row.querySelector(".supporting-queue-row__num");
    if (cell) cell.textContent = number;
  });
}

function insertRowAt(list, row, position) {
  const rows = list.querySelectorAll(".supporting-queue-row");
  if (rows.length === 0 || position >= rows.length) {
    list.appendChild(row);
  } else {
    let rowBefore = rows[position - 1];
    if (!rowBefore) rowBefore = rows[rows.length - 1];
    rowBefore.after(row);
  }
  renumberList(list);
}

function updateSkipBoard(skipBoard) {
  const shownChip = skipBoard.querySelector("[data-skip-chip]:not([hidden])");
  skipBoard.hidden = shownChip === null;
}

function skipQueueRow(panel, row) {
  const list = panel.querySelector("[data-queue-list]");
  const skipBoard = panel.querySelector("[data-skip-board]");
  const id = row.dataset.patientId;

  panel.querySelector("[data-parked-rows]").appendChild(row);
  renumberList(list);
  skipBoard.querySelector('[data-skip-chip="' + id + '"]').hidden = false;
  updateSkipBoard(skipBoard);
}

function reinsertSkippedPatient(panel, chip, position) {
  const id = chip.getAttribute("data-skip-chip");
  const row = panel.querySelector(
    '[data-parked-rows] [data-patient-id="' + id + '"]',
  );

  insertRowAt(panel.querySelector("[data-queue-list]"), row, position);
  chip.hidden = true;
  updateSkipBoard(panel.querySelector("[data-skip-board]"));
}

document.querySelectorAll("[data-queue-panel]").forEach((panel) => {
  const list = panel.querySelector("[data-queue-list]");
  if (!list) return;

  list.addEventListener("click", (event) => {
    const row = event.target.closest(".supporting-queue-row");
    if (!row) return;

    const button = event.target.closest("[data-act]");
    if (!button) {
      selectRowForVitals(row);
      return;
    }

    const action = button.dataset.act;
    if (action === "vitals") {
      selectRowForVitals(row);
    } else if (action === "up") {
      const above = row.previousElementSibling;
      if (above) {
        list.insertBefore(row, above);
        renumberList(list);
      }
    } else if (action === "down") {
      const below = row.nextElementSibling;
      if (below) {
        list.insertBefore(below, row);
        renumberList(list);
      }
    } else if (action === "skip") {
      skipQueueRow(panel, row);
    }
  });

  panel.querySelectorAll("[data-skip-chip]").forEach((chip) => {
    const button = chip.querySelector("[data-q-reinsert]");
    button.addEventListener("click", () => {
      const name = chip.querySelector("strong").textContent;
      openQueueReinsertPopup(button, "Reinsert " + name, (position) => {
        reinsertSkippedPatient(panel, chip, position);
      });
    });
  });
});

const queueReinsertPopup = document.getElementById("reinsert-pop");
let queueReinsertBox = null;
if (queueReinsertPopup)
  queueReinsertBox = queueReinsertPopup.querySelector(".reinsert-pop");
let onQueueReinsert = null;

function openQueueReinsertPopup(button, title, onConfirm) {
  if (!queueReinsertPopup) return;

  queueReinsertPopup.querySelector("[data-reinsert-title]").textContent = title;
  const positionInput = queueReinsertPopup.querySelector("[data-reinsert-pos]");
  positionInput.value = "3";
  queueReinsertPopup.hidden = false;

  const buttonBox = button.getBoundingClientRect();
  const rightmostLeft =
    window.scrollX +
    document.documentElement.clientWidth -
    queueReinsertBox.offsetWidth -
    12;
  let left = window.scrollX + buttonBox.left;
  if (left > rightmostLeft) left = rightmostLeft;
  if (left < window.scrollX + 8) left = window.scrollX + 8;
  queueReinsertBox.style.top = window.scrollY + buttonBox.bottom + 6 + "px";
  queueReinsertBox.style.left = left + "px";

  onQueueReinsert = onConfirm;
  positionInput.focus();
}

function closeQueueReinsertPopup() {
  if (queueReinsertPopup) queueReinsertPopup.hidden = true;
  onQueueReinsert = null;
}

if (queueReinsertPopup) {
  queueReinsertPopup
    .querySelector("[data-reinsert-cancel]")
    .addEventListener("click", closeQueueReinsertPopup);

  queueReinsertPopup
    .querySelector("[data-reinsert-confirm]")
    .addEventListener("click", () => {
      const position =
        parseInt(
          queueReinsertPopup.querySelector("[data-reinsert-pos]").value,
          10,
        ) || 3;
      if (onQueueReinsert) onQueueReinsert(position);
      closeQueueReinsertPopup();
    });

  document.addEventListener("click", (event) => {
    if (queueReinsertPopup.hidden) return;
    if (queueReinsertPopup.contains(event.target)) return;
    if (event.target.closest("[data-q-reinsert]")) return;
    closeQueueReinsertPopup();
  });
}

const weightInput = document.getElementById("v-weight");
const heightInput = document.getElementById("v-height");

function updateBmi() {
  const bmiDisplay = document.getElementById("v-bmi-display");
  if (!weightInput || !bmiDisplay) return;

  const weight = parseFloat(weightInput.value);
  let heightCm = 170;
  if (heightInput && parseFloat(heightInput.value))
    heightCm = parseFloat(heightInput.value);

  if (weight > 0 && heightCm > 0) {
    const heightM = heightCm / 100;
    bmiDisplay.textContent = (weight / (heightM * heightM)).toFixed(1);
  }
}

if (weightInput) weightInput.addEventListener("input", updateBmi);
if (heightInput) heightInput.addEventListener("input", updateBmi);

function setInputValue(id, value) {
  const input = document.getElementById(id);
  if (input) input.value = value;
}

function selectRowForVitals(row) {
  const patient = row.dataset;

  const title = document.getElementById("vitals-form-title");
  const subtitle = document.getElementById("vitals-form-sub");
  if (title) title.textContent = "Vitals - " + (patient.patientName || "");
  if (subtitle)
    subtitle.textContent = (patient.patientId || "") + " · selected for vitals";

  setInputValue("v-patient-id", patient.patientId || "");
  setInputValue("v-bp", patient.bp || "");
  setInputValue("v-temp", patient.temp || "");
  setInputValue("v-pulse", patient.pulse || "");
  setInputValue("v-spo2", patient.spo2 || "");
  setInputValue("v-weight", patient.weight || "");
  setInputValue("v-height", patient.height || "170");
  updateBmi();

  document
    .querySelectorAll(".supporting-queue-row")
    .forEach((other) => other.classList.remove("is-selected"));
  row.classList.add("is-selected");
}

const vitalsForm = document.getElementById("vitals-form");
if (vitalsForm) {
  vitalsForm.addEventListener("submit", (event) => {
    event.preventDefault();

    let patientId = "";
    const idInput = document.getElementById("v-patient-id");
    if (idInput) patientId = idInput.value;

    const row = document.querySelector(
      '.supporting-queue-row[data-patient-id="' + patientId + '"]',
    );
    if (row) {
      const pill = row.querySelector(".supporting-status-pill");
      if (pill) {
        pill.className = "supporting-status-pill supporting-status-pill--ready";
        pill.querySelector("[data-pill-label]").textContent = "Ready";
      }
    }
  });
}

const queueMessageBoard = document.getElementById("appt-msgboard");
const queueMessageEmpty = document.getElementById("appt-msg-empty");
const queueMessageInput = document.getElementById("appt-msg-input");
const queueMessagePost = document.getElementById("appt-msg-post");

function filterQueueMessageBoard() {
  if (!queueMessageBoard) return;
  let shownCount = 0;

  queueMessageBoard.querySelectorAll("[data-doc]").forEach((post) => {
    const postDoctor = post.getAttribute("data-doc");
    const show =
      queueDoctor === "all" ||
      postDoctor === "all" ||
      postDoctor === queueDoctor;
    post.hidden = !show;
    if (show) shownCount++;
  });

  if (queueMessageEmpty) queueMessageEmpty.hidden = shownCount !== 0;
}

function postQueueMessage() {
  if (!queueMessageInput || !queueMessageBoard) return;
  const text = queueMessageInput.value.trim();
  if (!text) return;

  const now = new Date();
  const time =
    String(now.getHours()).padStart(2, "0") +
    ":" +
    String(now.getMinutes()).padStart(2, "0");

  const template = document.getElementById("appt-msg-template");
  const post = template.content.firstElementChild.cloneNode(true);
  post.setAttribute("data-doc", queueDoctor);
  post.querySelector("p").textContent = text;
  post.querySelector(".message-board__time").textContent = time;

  if (queueMessageEmpty) {
    queueMessageBoard.insertBefore(post, queueMessageEmpty);
  } else {
    queueMessageBoard.appendChild(post);
  }
  queueMessageInput.value = "";
  filterQueueMessageBoard();
}

if (queueMessagePost)
  queueMessagePost.addEventListener("click", postQueueMessage);
if (queueMessageInput) {
  queueMessageInput.addEventListener("keydown", (event) => {
    if (event.key === "Enter") {
      event.preventDefault();
      postQueueMessage();
    }
  });
}
filterQueueMessageBoard();

const emergencyModal = document.getElementById("emergency-modal");

function openEmergencyModal() {
  emergencyModal.hidden = false;
}

function closeEmergencyModal() {
  emergencyModal.hidden = true;
}

if (emergencyModal) {
  const openButton = document.getElementById("emergency-btn");
  const closeButton = document.getElementById("emergency-modal-close");
  const cancelButton = document.getElementById("emergency-cancel-btn");
  if (openButton) openButton.addEventListener("click", openEmergencyModal);
  if (closeButton) closeButton.addEventListener("click", closeEmergencyModal);
  if (cancelButton) cancelButton.addEventListener("click", closeEmergencyModal);

  emergencyModal.addEventListener("click", (event) => {
    if (event.target === emergencyModal) closeEmergencyModal();
  });
  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && !emergencyModal.hidden) closeEmergencyModal();
  });

  const emergencyForm = document.getElementById("emergency-form");
  if (emergencyForm) {
    emergencyForm.addEventListener("submit", (event) => {
      event.preventDefault();
      closeEmergencyModal();
    });
  }
}
