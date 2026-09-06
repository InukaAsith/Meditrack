const historySubtabGroups = document.querySelectorAll("[data-subtabs]");

for (const group of historySubtabGroups) {
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

const searchModeBar = document.querySelector("[data-search-mode]");
const historySearch = document.getElementById("ph-search");

if (searchModeBar) {
  searchModeBar.addEventListener("click", (event) => {
    const button = event.target.closest("[data-mode]");
    if (!button) return;

    for (const other of searchModeBar.querySelectorAll("[data-mode]")) {
      other.classList.toggle("is-active", other === button);
    }

    if (!historySearch) return;
    if (button.getAttribute("data-mode") === "appointment") {
      historySearch.placeholder =
        "Search by appointment code (APT-1120) or date…";
    } else {
      historySearch.placeholder = "Search by patient ID (PT-0912) or name…";
    }
  });
}

if (historySearch) {
  historySearch.addEventListener("input", () => {
    const query = historySearch.value.toLowerCase();
    const rows = document.querySelectorAll("[data-ph-row]");
    for (const row of rows) {
      if (row.textContent.toLowerCase().includes(query)) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    }
  });
}
