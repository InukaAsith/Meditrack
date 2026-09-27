document.addEventListener("DOMContentLoaded", () => {
  const searchBox = document.getElementById("billing-search");
  searchBox.addEventListener("input", () => filterInvoices(searchBox.value));

  document.querySelectorAll("[data-print]").forEach((button) => {
    button.addEventListener("click", () => window.print());
  });
});

function filterInvoices(text) {
  const query = text.trim().toLowerCase();
  const words = query.split(/\s+/).filter(Boolean);
  let shownCount = 0;

  document.querySelectorAll("tr[data-search]").forEach((row) => {
    const searchAttr = row.getAttribute("data-search") || "";
    const matches = words.length === 0 || words.every((w) => searchAttr.includes(w));
    row.hidden = !matches;
    if (matches) shownCount++;
  });

  document.getElementById("billing-empty").hidden = shownCount !== 0;
}
