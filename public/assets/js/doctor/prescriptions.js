const rxSearch = document.getElementById("rx-search");
const rxRows = document.querySelectorAll(".data-table__row[data-status]");

if (rxSearch) {
  rxSearch.addEventListener("input", () => {
    const query = rxSearch.value.toLowerCase();
    for (const row of rxRows) {
      if (row.textContent.toLowerCase().includes(query)) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    }
  });
}

document.addEventListener("click", (event) => {
  const pill = event.target.closest(".staff-pill[data-tab]");
  if (!pill) return;

  const status = pill.getAttribute("data-tab");
  for (const row of rxRows) {
    if (status === "all" || row.getAttribute("data-status") === status) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  }
});
