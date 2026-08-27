const requiredBatchFields = [
  { name: "commercial_name", label: "Brand name" },
  { name: "generic", label: "Generic name" },
  { name: "supplier", label: "Supplier" },
  { name: "invoice_ref", label: "Supplier invoice number" },
  { name: "batch_id", label: "Batch number" },
  { name: "expiry_date", label: "Expiry date" },
  { name: "qty_received", label: "Quantity received" },
];

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("batch-form");
  form.addEventListener("submit", (event) => checkBatchForm(event, form));
});

function checkBatchForm(event, form) {
  const missing = [];
  let firstEmptyField = null;

  for (const required of requiredBatchFields) {
    const field = form.querySelector('[name="' + required.name + '"]');
    if (field.value.trim() === "") {
      missing.push(required.label);
      if (firstEmptyField === null) firstEmptyField = field;
    }
  }

  document.getElementById("batch-form-missing").textContent = missing.join(", ");
  document.getElementById("batch-form-error").hidden = missing.length === 0;

  if (missing.length > 0) {
    event.preventDefault();
    firstEmptyField.focus();
  }
}
