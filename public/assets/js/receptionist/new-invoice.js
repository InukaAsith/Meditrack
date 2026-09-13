const invoicePage = document.querySelector("[data-new-invoice]");
const invoiceResults = document.querySelectorAll("[data-new-invoice] .new-invoice-res");
const invoiceGuestToggle = document.getElementById("new-invoice-guest-toggle");
const invoiceGuestBox = document.getElementById("new-invoice-guest");
const invoiceGuestName = document.getElementById("new-invoice-guest-name");
const invoiceDoctor = document.getElementById("new-invoice-doc");
const invoiceAppointment = document.getElementById("new-invoice-appt");
const invoiceAmount = document.getElementById("new-invoice-amount");

if (invoicePage) {
  setUpInvoiceSearch();
  setUpInvoicePatientPicking();
  setUpInvoiceInputs();
  updateInvoicePreview();
}

function formatInvoiceMoney(value) {
  let amount = parseFloat(value);
  if (isNaN(amount)) amount = 0;
  return "Rs. " + amount.toLocaleString("en-LK", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function invoicePatientLabel() {
  if (invoiceGuestToggle.checked) {
    const guestName = invoiceGuestName.value.trim() || "Unregistered guest";
    return guestName + " (guest)";
  }

  const picked = invoicePage.querySelector(".new-invoice-res.is-picked");
  if (!picked) return "Not picked yet";
  return picked.getAttribute("data-name") + " (" + picked.getAttribute("data-code") + ")";
}

function setInvoiceSummary(selector, text) {
  const element = invoicePage.querySelector(selector);
  if (element) element.textContent = text;
}

function updateInvoicePreview() {
  setInvoiceSummary("[data-sum-patient]", invoicePatientLabel());
  setInvoiceSummary("[data-sum-doctor]", invoiceDoctor.value);
  setInvoiceSummary("[data-sum-appt]", invoiceAppointment.value.trim() || "Manual check-in");
  setInvoiceSummary("[data-sum-amount]", formatInvoiceMoney(invoiceAmount.value));
}

function setUpInvoiceSearch() {
  const searchBox = document.getElementById("new-invoice-search");
  const noResults = document.getElementById("new-invoice-empty");
  if (!searchBox) return;

  searchBox.addEventListener("input", () => {
    const query = searchBox.value.trim().toLowerCase();
    let shownCount = 0;
    invoiceResults.forEach((result) => {
      const matches = query === "" || result.getAttribute("data-hay").includes(query);
      result.closest("[data-invoice-result]").hidden = !matches;
      if (matches) shownCount++;
    });
    if (noResults) noResults.hidden = shownCount !== 0;
  });
}

function setUpInvoicePatientPicking() {
  invoiceResults.forEach((result) => {
    result.addEventListener("click", () => {
      invoiceResults.forEach((other) => other.classList.toggle("is-picked", other === result));
      if (invoiceGuestToggle.checked) {
        invoiceGuestToggle.checked = false;
        invoiceGuestBox.hidden = true;
      }
      updateInvoicePreview();
    });
  });

  if (invoiceGuestToggle) {
    invoiceGuestToggle.addEventListener("change", () => {
      invoiceGuestBox.hidden = !invoiceGuestToggle.checked;
      if (invoiceGuestToggle.checked) {
        invoiceResults.forEach((result) => result.classList.remove("is-picked"));
      }
      updateInvoicePreview();
    });
  }
}

function setUpInvoiceInputs() {
  if (invoiceDoctor) {
    invoiceDoctor.addEventListener("change", () => {
      const option = invoiceDoctor.options[invoiceDoctor.selectedIndex];
      if (option) {
        const fee = option.getAttribute("data-fee");
        if (fee && fee !== "0" && invoiceAmount) invoiceAmount.value = parseFloat(fee).toFixed(2);
      }
      updateInvoicePreview();
    });
  }

  if (invoiceAppointment) invoiceAppointment.addEventListener("input", updateInvoicePreview);
  if (invoiceAmount) invoiceAmount.addEventListener("input", updateInvoicePreview);
  if (invoiceGuestName) invoiceGuestName.addEventListener("input", updateInvoicePreview);

  const createButton = document.getElementById("new-invoice-create");
  if (!createButton) return;

  createButton.addEventListener("click", () => {
    const noPatient = invoicePatientLabel() === "Not picked yet";
    document.getElementById("new-invoice-error").hidden = !noPatient;
    if (noPatient) return;

    window.location.href = "/staff/receptionist/billing";
  });
}
