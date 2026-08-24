const reinsertPopup = document.getElementById("reinsert-pop");
let reinsertBox = null;
if (reinsertPopup) reinsertBox = reinsertPopup.querySelector(".reinsert-pop");
let onReinsertConfirm = null;

function closeRowMenus() {
  document.querySelectorAll("[data-rowmenu][data-open]").forEach((menu) => {
    menu.removeAttribute("data-open");
    const popup = menu.querySelector("[data-rowmenu-pop]");
    if (popup) popup.hidden = true;
    const toggle = menu.querySelector("[data-rowmenu-toggle]");
    if (toggle) toggle.setAttribute("aria-expanded", "false");
  });
}

document.addEventListener("click", (event) => {
  if (!event.target.closest("[data-rowmenu]")) closeRowMenus();
});

function toggleRowMenu(toggle) {
  const menu = toggle.closest("[data-rowmenu]");
  const wasOpen = menu.hasAttribute("data-open");
  closeRowMenus();
  if (wasOpen) return;

  menu.setAttribute("data-open", "");
  menu.querySelector("[data-rowmenu-pop]").hidden = false;
  toggle.setAttribute("aria-expanded", "true");
}

function renumberQueue(tbody) {
  let number = 0;
  tbody.querySelectorAll("[data-queue-row]").forEach((row) => {
    const badge = row.querySelector(".appt-qnum__badge");
    if (!badge) return;

    if (row.getAttribute("data-inqueue") === "1" && !row.hidden) {
      number++;
      badge.textContent = "#" + number;
    }
  });
}

function rowsInQueue(tbody) {
  const rows = [];
  tbody.querySelectorAll("[data-queue-row]").forEach((row) => {
    if (row.getAttribute("data-inqueue") === "1") rows.push(row);
  });
  return rows;
}

function moveRowUp(tbody, row) {
  let above = row.previousElementSibling;
  while (above && above.getAttribute("data-inqueue") !== "1") {
    above = above.previousElementSibling;
  }
  if (!above) return;

  tbody.insertBefore(row, above);
  renumberQueue(tbody);
}

function moveRowDown(tbody, row) {
  let below = row.nextElementSibling;
  while (below && below.getAttribute("data-inqueue") !== "1") {
    below = below.nextElementSibling;
  }
  if (!below) return;

  tbody.insertBefore(below, row);
  renumberQueue(tbody);
}

function insertAfterPosition(tbody, row, position) {
  const queue = rowsInQueue(tbody);
  row.setAttribute("data-inqueue", "1");
  row.hidden = false;

  if (queue.length === 0 || position >= queue.length) {
    tbody.appendChild(row);
  } else {
    let rowBefore = queue[position - 1];
    if (!rowBefore) rowBefore = queue[queue.length - 1];
    rowBefore.after(row);
  }
  renumberQueue(tbody);
}

function updateSkipBoard(skipBoard) {
  const shownChip = skipBoard.querySelector("[data-skip-chip]:not([hidden])");
  skipBoard.hidden = shownChip === null;
}

function skipPatient(tbody, row, skipBoard, skippedRows) {
  const code = row.getAttribute("data-patient");

  row.remove();
  row.setAttribute("data-inqueue", "0");
  skippedRows[code] = row;
  renumberQueue(tbody);
  skipBoard.querySelector('[data-skip-chip="' + code + '"]').hidden = false;
  updateSkipBoard(skipBoard);
}

function reinsertPatient(tbody, chip, skipBoard, skippedRows, position) {
  const code = chip.getAttribute("data-skip-chip");
  insertAfterPosition(tbody, skippedRows[code], position);
  delete skippedRows[code];
  chip.hidden = true;
  updateSkipBoard(skipBoard);
}

function openReinsertPopup(button, title, onConfirm) {
  if (!reinsertPopup) return;

  reinsertPopup.querySelector("[data-reinsert-title]").textContent = title;
  const positionInput = reinsertPopup.querySelector("[data-reinsert-pos]");
  positionInput.value = "3";
  reinsertPopup.hidden = false;

  const buttonBox = button.getBoundingClientRect();
  const rightmostLeft =
    window.scrollX +
    document.documentElement.clientWidth -
    reinsertBox.offsetWidth -
    12;
  let left = window.scrollX + buttonBox.left;
  if (left > rightmostLeft) left = rightmostLeft;
  if (left < window.scrollX + 8) left = window.scrollX + 8;
  reinsertBox.style.top = window.scrollY + buttonBox.bottom + 6 + "px";
  reinsertBox.style.left = left + "px";

  onReinsertConfirm = onConfirm;
  positionInput.focus();
}

function closeReinsertPopup() {
  if (reinsertPopup) reinsertPopup.hidden = true;
  onReinsertConfirm = null;
}

if (reinsertPopup) {
  reinsertPopup
    .querySelector("[data-reinsert-cancel]")
    .addEventListener("click", closeReinsertPopup);

  reinsertPopup
    .querySelector("[data-reinsert-confirm]")
    .addEventListener("click", () => {
      const position =
        parseInt(
          reinsertPopup.querySelector("[data-reinsert-pos]").value,
          10,
        ) || 3;
      if (onReinsertConfirm) onReinsertConfirm(position);
      closeReinsertPopup();
    });

  document.addEventListener("click", (event) => {
    if (reinsertPopup.hidden) return;
    if (reinsertPopup.contains(event.target)) return;
    if (event.target.closest("[data-q-reinsert]")) return;
    closeReinsertPopup();
  });
}

document.querySelectorAll("[data-queue]").forEach((table) => {
  const tbody = table.tBodies[0];
  const section = table.closest("section") || document;
  const skipBoard = section.querySelector("[data-skip-board]");
  const skippedRows = {};

  if (skipBoard) {
    skipBoard.querySelectorAll("[data-skip-chip]").forEach((chip) => {
      const reinsertButton = chip.querySelector("[data-q-reinsert]");
      reinsertButton.addEventListener("click", () => {
        const name = chip.querySelector("strong").textContent;
        openReinsertPopup(reinsertButton, "Reinsert " + name, (position) => {
          reinsertPatient(tbody, chip, skipBoard, skippedRows, position);
        });
      });
    });
  }

  tbody.addEventListener("click", (event) => {
    const row = event.target.closest("[data-queue-row]");
    if (!row) return;

    if (event.target.closest("[data-q-up]")) {
      moveRowUp(tbody, row);
    } else if (event.target.closest("[data-q-down]")) {
      moveRowDown(tbody, row);
    } else if (event.target.closest("[data-q-skip]")) {
      if (skipBoard) skipPatient(tbody, row, skipBoard, skippedRows);
    } else if (event.target.closest("[data-rowmenu-toggle]")) {
      toggleRowMenu(event.target.closest("[data-rowmenu-toggle]"));
    }
  });
});
