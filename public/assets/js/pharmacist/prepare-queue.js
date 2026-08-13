let queueTypeFilter = "all";

document.addEventListener("DOMContentLoaded", () => {
  document.getElementById("queue-search").addEventListener("input", filterQueue);
  setUpTypePills();
  setUpDismissButtons();
  followLinkParams();
  filterQueue();
});

function showQueueTab(key) {
  document.querySelector('[data-tab="' + key + '"]').click();
}

function takeOffList(row) {
  row.removeAttribute("data-queue-row");
  row.hidden = true;
}

function filterQueue() {
  const query = document.getElementById("queue-search").value.trim().toLowerCase();

  document.querySelectorAll("[data-queue-row]").forEach((row) => {
    const matchesType = queueTypeFilter === "all" || row.getAttribute("data-type") === queueTypeFilter;
    const matchesText = row.getAttribute("data-search").includes(query);
    row.hidden = !(matchesType && matchesText);
  });

  updateQueueCounts();
}

function updateQueueCounts() {
  for (const key of ["to-prepare", "prepared"]) {
    const panel = document.querySelector('[data-tab-panel="' + key + '"]');
    const rows = panel.querySelectorAll("[data-queue-row]");

    let visibleCount = 0;
    rows.forEach((row) => {
      if (!row.hidden) visibleCount++;
    });

    document.getElementById(key + "-count").textContent = rows.length;
    panel.querySelector("[data-queue-empty]").hidden = visibleCount !== 0;
  }
}

function setUpTypePills() {
  const pills = document.querySelectorAll("[data-type-filter]");
  pills.forEach((pill) => {
    pill.addEventListener("click", () => {
      pills.forEach((other) => other.classList.toggle("is-active", other === pill));
      queueTypeFilter = pill.getAttribute("data-type-filter");
      filterQueue();
    });
  });
}

function setUpDismissButtons() {
  document.querySelectorAll("[data-dismiss]").forEach((button) => {
    button.addEventListener("click", () => {
      takeOffList(button.closest("tr"));
      updateQueueCounts();
    });
  });
}

function markPrepared(order) {
  const row = document.querySelector('[data-tab-panel="to-prepare"] [data-order="' + order + '"]');
  const copy = document.querySelector('[data-prepared-copy="' + order + '"]');
  if (row) takeOffList(row);
  if (copy) {
    copy.setAttribute("data-queue-row", "");
    copy.setAttribute("data-order", order);
  }
}

function markCollected(order) {
  const row = document.querySelector('[data-tab-panel="prepared"] [data-order="' + order + '"]');
  if (row) takeOffList(row);
}

function followLinkParams() {
  const params = new URLSearchParams(window.location.search);

  if (params.get("prepared")) {
    markPrepared(params.get("prepared"));
    showQueueTab("prepared");
  }
  if (params.get("collected")) {
    markCollected(params.get("collected"));
    showQueueTab("prepared");
  }
  if (params.get("tab") === "prepared") showQueueTab("prepared");

  if (params.get("filter")) {
    const pill = document.querySelector('[data-type-filter="' + params.get("filter") + '"]');
    if (pill) pill.click();
  }
}
