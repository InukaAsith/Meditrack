const recordsList = document.getElementById("records-list");
const recordsDetail = document.getElementById("records-detail");

if (recordsList && recordsDetail) {
  const openButtons = document.querySelectorAll("[data-open-record]");
  for (const btn of openButtons) {
    btn.addEventListener("click", () => showRecordsSection("detail"));
  }

  const backButton = document.querySelector("[data-close-record]");
  if (backButton) {
    backButton.addEventListener("click", () => showRecordsSection("list"));
  }
}

function showRecordsSection(which) {
  recordsList.hidden = which !== "list";
  recordsDetail.hidden = which !== "detail";
  window.scrollTo({ top: 0, behavior: "smooth" });
}
