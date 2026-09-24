document.addEventListener("DOMContentLoaded", () => {
  setUpVitalsSearch();
  setUpRowHighlight();
  setUpEmergencyModal();
});

function setUpVitalsSearch() {
  const searchBox = document.getElementById("vitals-search-input");
  const table = document.getElementById("vitals-history-table");
  if (!searchBox || !table) return;

  searchBox.addEventListener("input", () => {
    const query = searchBox.value.toLowerCase().trim();
    const rows = table.querySelectorAll("tbody tr");
    let shownCount = 0;

    rows.forEach((row) => {
      const matches = row.textContent.toLowerCase().includes(query);
      row.hidden = !matches;
      if (matches) shownCount++;
    });

    const badge = document.querySelector(".supporting-vh-header__badge");
    if (!badge) return;
    if (query) {
      badge.textContent = shownCount + " of " + rows.length + " patients shown";
    } else {
      badge.textContent = rows.length + " patients · all flipped to Ready";
    }
  });
}

function setUpRowHighlight() {
  const table = document.getElementById("vitals-history-table");
  if (!table) return;

  table.addEventListener("click", (event) => {
    const row = event.target.closest(".supporting-vh-row");
    if (!row) return;

    table.querySelectorAll(".supporting-vh-row").forEach((other) => {
      other.classList.toggle("is-selected", other === row);
    });
  });
}

function setUpEmergencyModal() {
  const modal = document.getElementById("emergency-modal");
  if (!modal) return;

  function openModal() {
    modal.hidden = false;
  }
  function closeModal() {
    modal.hidden = true;
  }

  const openButton = document.getElementById("emergency-btn");
  const closeButton = document.getElementById("emergency-modal-close");
  const cancelButton = document.getElementById("emergency-cancel-btn");
  if (openButton) openButton.addEventListener("click", openModal);
  if (closeButton) closeButton.addEventListener("click", closeModal);
  if (cancelButton) cancelButton.addEventListener("click", closeModal);

  modal.addEventListener("click", (event) => {
    if (event.target === modal) closeModal();
  });
  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && !modal.hidden) closeModal();
  });

  const form = document.getElementById("emergency-form");
  if (form) {
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      closeModal();
    });
  }
}
