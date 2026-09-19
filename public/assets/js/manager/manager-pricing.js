const pricingPage = document.querySelector("[data-pricing]");

if (pricingPage) setUpDrugPricing();

function formatPrice(price) {
  if (Number.isInteger(price)) return "Rs. " + price;
  return "Rs. " + price.toFixed(2);
}

function setUpDrugPricing() {
  const modal = document.querySelector("[data-price-modal]");
  const newPriceInput = modal.querySelector("[data-price-new]");
  const today = pricingPage.getAttribute("data-today") || "Today";
  const actor = pricingPage.getAttribute("data-actor") || "You";
  let editingRow = null;

  document.addEventListener("click", (event) => {
    const adjustButton = event.target.closest("[data-price-open]");
    if (adjustButton) {
      editingRow = adjustButton.closest("[data-drug-row]");
      const currentPrice = editingRow
        .querySelector('[data-cell="price"]')
        .textContent.trim();

      modal.querySelector("[data-price-name]").textContent =
        editingRow.getAttribute("data-name");
      modal.querySelector("[data-price-generic]").textContent =
        editingRow.getAttribute("data-generic");
      modal.querySelector("[data-price-current]").value = currentPrice;
      newPriceInput.value =
        parseFloat(currentPrice.replace(/[^0-9.]/g, "")) || 0;

      modal.querySelector("[data-price-error]").hidden = true;
      modal.hidden = false;
      newPriceInput.focus();
      newPriceInput.select();
      return;
    }

    if (event.target.closest("[data-price-close]") || event.target === modal) {
      modal.hidden = true;
      return;
    }

    if (event.target.closest("[data-price-save]") && editingRow) {
      const newPrice = parseFloat(newPriceInput.value);
      if (isNaN(newPrice) || newPrice < 0) {
        modal.querySelector("[data-price-error]").hidden = false;
        newPriceInput.focus();
        return;
      }

      editingRow.querySelector('[data-cell="price"]').textContent =
        formatPrice(newPrice);
      editingRow.querySelector('[data-cell="changed"]').textContent = today;
      editingRow.querySelector('[data-cell="by"]').textContent = actor;
      modal.hidden = true;
    }
  });

  const searchBox = pricingPage.querySelector("[data-price-search]");
  const noResults = pricingPage.querySelector("[data-price-empty]");
  if (!searchBox) return;

  searchBox.addEventListener("input", () => {
    const query = searchBox.value.trim().toLowerCase();
    let shownCount = 0;

    pricingPage.querySelectorAll("[data-drug-row]").forEach((row) => {
      const names = (
        row.getAttribute("data-name") +
        " " +
        row.getAttribute("data-generic")
      ).toLowerCase();
      const matches = names.includes(query);
      row.hidden = !matches;
      if (matches) shownCount++;
    });

    if (noResults) noResults.hidden = shownCount !== 0;
  });
}
