function showPatientsPanel(name) {
  document.querySelectorAll("[data-pt-panel]").forEach((panel) => {
    panel.hidden = panel.getAttribute("data-pt-panel") !== name;
  });

  if (!name.startsWith("appt-")) {
    let mode = "patients";
    if (name === "all-appts") mode = "all-appts";
    document.querySelectorAll("[data-pt-mode]").forEach((button) => {
      button.classList.toggle(
        "is-active",
        button.getAttribute("data-pt-mode") === mode,
      );
    });
  }

  window.scrollTo({ top: 0, behavior: "smooth" });
}

document.querySelectorAll("[data-pt-tab]").forEach((button) => {
  button.addEventListener("click", () =>
    showPatientsPanel(button.getAttribute("data-pt-tab")),
  );
});

document.querySelectorAll("[data-pt-mode]").forEach((button) => {
  button.addEventListener("click", () => {
    if (button.getAttribute("data-pt-mode") === "all-appts") {
      showPatientsPanel("all-appts");
    } else {
      showPatientsPanel("search");
    }
  });
});

const patientSearch = document.getElementById("patient-find-search");

function filterPatientResults(text) {
  const results = document.getElementById("patient-find-results");
  if (!results) return;

  const query = text.toLowerCase().trim();
  results.querySelectorAll("[data-name]").forEach((row) => {
    const name = row.getAttribute("data-name");
    row.hidden = !(query === "" || name.includes(query));
  });
}

if (patientSearch) {
  patientSearch.addEventListener("input", () => {
    filterPatientResults(patientSearch.value);
  });
}

const allApptsList = document.getElementById("aa-results");
const allApptsSearch = document.getElementById("aa-search");
const allApptsDate = document.getElementById("aa-date");
const allApptsDoctor = document.getElementById("aa-doc");
const allApptsSort = document.getElementById("aa-sort");
const allApptsEmpty = document.getElementById("aa-empty");

let allApptsRows = [];

if (allApptsList) {
  allApptsRows = Array.from(allApptsList.querySelectorAll("[data-appt-row]"));
  if (allApptsSearch) allApptsSearch.addEventListener("input", filterAllAppts);
  if (allApptsDate) allApptsDate.addEventListener("change", filterAllAppts);
  if (allApptsDoctor) allApptsDoctor.addEventListener("change", filterAllAppts);
  if (allApptsSort) allApptsSort.addEventListener("change", sortAllAppts);
  sortAllAppts();
}

function filterAllAppts() {
  let query = "";
  if (allApptsSearch) query = allApptsSearch.value.toLowerCase().trim();
  let date = "";
  if (allApptsDate) date = allApptsDate.value;
  let doctor = "all";
  if (allApptsDoctor && allApptsDoctor.value) doctor = allApptsDoctor.value;

  let shownCount = 0;
  allApptsRows.forEach((row) => {
    let show = true;
    if (query && !(row.getAttribute("data-hay") || "").includes(query))
      show = false;
    if (date && row.getAttribute("data-date") !== date) show = false;
    if (doctor !== "all" && row.getAttribute("data-doc") !== doctor)
      show = false;

    row.hidden = !show;
    if (show) shownCount++;
  });

  if (allApptsEmpty) allApptsEmpty.hidden = shownCount !== 0;
}

function sortAllAppts() {
  let sortBy = "appt";
  if (allApptsSort && allApptsSort.value) sortBy = allApptsSort.value;

  const rows = allApptsRows.slice();
  rows.sort((a, b) => {
    if (sortBy === "date") {
      const aKey = a.getAttribute("data-date") + a.getAttribute("data-apptid");
      const bKey = b.getAttribute("data-date") + b.getAttribute("data-apptid");
      return aKey.localeCompare(bKey);
    }
    if (sortBy === "patient") {
      return a
        .getAttribute("data-patient")
        .localeCompare(b.getAttribute("data-patient"));
    }
    return a
      .getAttribute("data-apptid")
      .localeCompare(b.getAttribute("data-apptid"));
  });

  rows.forEach((row) => allApptsList.insertBefore(row, allApptsEmpty));
}

const deepLinkPanel = new URLSearchParams(location.search).get("panel");
if (deepLinkPanel) showPatientsPanel(deepLinkPanel);
