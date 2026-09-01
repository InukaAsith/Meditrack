document.addEventListener("DOMContentLoaded", () => {
  const searchBox = document.getElementById("billing-search");
  searchBox.addEventListener("input", () => filterInvoices(searchBox.value));

  document.querySelectorAll("[data-print]").forEach((button) => {
    button.addEventListener("click", () => window.print());
  });
});

function filterInvoices(text) {
  const query = text.trim().toLowerCase();
  let shownCount = 0;

  document.querySelectorAll("tr[data-search]").forEach((row) => {
    const matches = row.getAttribute("data-search").includes(query);
    row.hidden = !matches;
    if (matches) shownCount++;
  });

  document.getElementById("billing-empty").hidden = shownCount !== 0;
}
