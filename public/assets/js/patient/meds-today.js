const MEDS_TAKEN_KEY = "meditrack.meds-taken";

const medRows = document.querySelectorAll(".med-row[data-med]");

if (medRows.length) {
  const takenState = loadMedsTakenState();

  for (const row of medRows) {
    const id = row.getAttribute("data-med");

    if (Object.prototype.hasOwnProperty.call(takenState, id)) {
      applyMedRowState(row, !!takenState[id]);
    }
    row.classList.toggle(
      "is-taken",
      row.getAttribute("aria-pressed") === "true",
    );

    row.addEventListener("click", () => {
      const taken = row.getAttribute("aria-pressed") !== "true";
      applyMedRowState(row, taken);
      takenState[id] = taken;
      saveMedsTakenState(takenState);
    });
  }
}

function loadMedsTakenState() {
  try {
    return JSON.parse(localStorage.getItem(MEDS_TAKEN_KEY) || "{}");
  } catch (err) {
    return {};
  }
}

function saveMedsTakenState(state) {
  try {
    localStorage.setItem(MEDS_TAKEN_KEY, JSON.stringify(state));
  } catch (err) {
  }
}

function applyMedRowState(row, taken) {
  row.setAttribute("aria-pressed", String(taken));
  row.classList.toggle("is-taken", taken);

  const check = row.querySelector(".med-row__check");
  if (check) check.classList.toggle("is-done", taken);
}
