

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
  if (!form) return;

  form.addEventListener("submit", (event) => checkBatchForm(event, form));

  const commercialInput = form.querySelector('[name="commercial_name"]');
  const medicinesDataEl = document.getElementById("medicines-data");
  let medicinesData = [];
  if (medicinesDataEl) {
    try {
      medicinesData = JSON.parse(medicinesDataEl.textContent);
    } catch (_) {}
  }

  if (commercialInput && medicinesData.length > 0) {
    commercialInput.addEventListener("input", () => {
      const val = commercialInput.value.trim().toLowerCase();
      if (!val) return;
      const matched = medicinesData.find(
        (m) => (m.commercial_name || "").toLowerCase() === val
      );
      if (matched) {
        if (matched.generic_name) {
          const genericField = form.querySelector('[name="generic"]');
          if (genericField) genericField.value = matched.generic_name;
        }
        if (matched.unit_form) {
          const formSelect = form.querySelector('[name="unit_form"]');
          if (formSelect) {
            for (const opt of formSelect.options) {
              if (opt.value.toLowerCase() === matched.unit_form.toLowerCase()) {
                opt.selected = true;
                break;
              }
            }
          }
        }
        if (matched.manufacturer) {
          const mfgField = form.querySelector('[name="manufacturer"]');
          if (mfgField) mfgField.value = matched.manufacturer;
        }
        if (matched.storage_limits) {
          const storageField = form.querySelector('[name="storage_limits"]');
          if (storageField) storageField.value = matched.storage_limits;
        }
        if (matched.unit_price !== undefined && matched.unit_price !== null) {
          const priceField = form.querySelector('[name="unit_price"]');
          if (priceField) priceField.value = parseFloat(matched.unit_price).toFixed(2);
        }
        if (matched.reorder_threshold !== undefined && matched.reorder_threshold !== null) {
          const thresholdField = form.querySelector('[name="reorder_threshold"]');
          if (thresholdField) thresholdField.value = matched.reorder_threshold;
        }
      }
    });
  }
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

  const qtyInput = form.querySelector('[name="qty_received"]');
  if (qtyInput && qtyInput.value.trim() !== "") {
    const qtyRaw = qtyInput.value.trim();
    if (!/^\d+$/.test(qtyRaw) || parseInt(qtyRaw, 10) <= 0) {
      missing.push("Quantity received (positive whole number, digits only)");
      if (firstEmptyField === null) firstEmptyField = qtyInput;
    }
  }

  const costInput = form.querySelector('[name="total_cost"]');
  if (costInput && costInput.value.trim() !== "") {
    const costRaw = costInput.value.trim();
    if (!/^\d+(\.\d{1,2})?$/.test(costRaw) || parseFloat(costRaw) < 0) {
      missing.push("Total cost (valid rupees amount, e.g. 4500.00)");
      if (firstEmptyField === null) firstEmptyField = costInput;
    }
  }

  const unitPriceInput = form.querySelector('[name="unit_price"]');
  if (unitPriceInput && unitPriceInput.value.trim() !== "") {
    const pRaw = unitPriceInput.value.trim();
    if (!/^\d+(\.\d{1,2})?$/.test(pRaw) || parseFloat(pRaw) <= 0) {
      missing.push("Unit selling price (must be greater than Rs. 0.00)");
      if (firstEmptyField === null) firstEmptyField = unitPriceInput;
    }
  }

  const threshInput = form.querySelector('[name="reorder_threshold"]');
  if (threshInput && threshInput.value.trim() !== "") {
    const tRaw = threshInput.value.trim();
    if (!/^\d+$/.test(tRaw) || parseInt(tRaw, 10) < 0 || parseInt(tRaw, 10) > 10000) {
      missing.push("Reorder threshold (number between 0 and 10,000)");
      if (firstEmptyField === null) firstEmptyField = threshInput;
    }
  }

  const expiryInput = form.querySelector('[name="expiry_date"]');
  if (expiryInput && expiryInput.value.trim() !== "") {
    const val = expiryInput.value.trim();
    let y = null, m = null, d = null;
    const isoMatch = val.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/);
    const dmyMatch = val.match(/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/);
    if (isoMatch) {
      y = parseInt(isoMatch[1], 10);
      m = parseInt(isoMatch[2], 10);
      d = parseInt(isoMatch[3], 10);
    } else if (dmyMatch) {
      d = parseInt(dmyMatch[1], 10);
      m = parseInt(dmyMatch[2], 10);
      y = parseInt(dmyMatch[3], 10);
    }

    if (!y || !m || !d) {
      missing.push("Valid expiry date");
      if (firstEmptyField === null) firstEmptyField = expiryInput;
    } else {
      const dt = new Date(y, m - 1, d);
      const isRealDate = dt.getFullYear() === y && (dt.getMonth() + 1) === m && dt.getDate() === d;
      if (!isRealDate) {
        missing.push("Valid calendar date for expiry");
        if (firstEmptyField === null) firstEmptyField = expiryInput;
      } else {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (dt <= today) {
          missing.push("Future expiry date (cannot be in the past or today)");
          if (firstEmptyField === null) firstEmptyField = expiryInput;
        }
      }
    }
  }

  if (missing.length > 0) {
    event.preventDefault();
    document.getElementById("batch-form-missing").textContent = missing.join(", ");
    document.getElementById("batch-form-error").hidden = false;
    firstEmptyField.focus();
    return;
  }

  const batchInput = form.querySelector('[name="batch_id"]');
  const batchCode = batchInput ? batchInput.value.trim().toUpperCase() : "";
  const batchesDataEl = document.getElementById("batches-data");
  let existingBatches = [];
  if (batchesDataEl) {
    try {
      existingBatches = JSON.parse(batchesDataEl.textContent);
    } catch (_) {}
  }

  if (batchCode && existingBatches.includes(batchCode)) {
    event.preventDefault();
    const errorEl = document.getElementById("batch-form-error");
    errorEl.innerHTML = 'Batch number <strong>' + batchCode + '</strong> already exists. Batch numbers must be unique.';
    errorEl.hidden = false;
    batchInput.focus();
    return;
  }

  document.getElementById("batch-form-error").hidden = true;
}
