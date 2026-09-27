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
  const medicinesData = batchMedicines();

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
  const invalid = [];
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
      invalid.push("quantity received (a whole number above 0)");
      if (firstEmptyField === null) firstEmptyField = qtyInput;
    }
  }

  const costInput = form.querySelector('[name="total_cost"]');
  if (costInput && costInput.value.trim() !== "") {
    const costRaw = costInput.value.trim();
    if (!/^\d+(\.\d{1,2})?$/.test(costRaw) || parseFloat(costRaw) < 0) {
      invalid.push("total cost (rupees, up to 2 decimals)");
      if (firstEmptyField === null) firstEmptyField = costInput;
    }
  }

  const unitPriceInput = form.querySelector('[name="unit_price"]');
  if (unitPriceInput && unitPriceInput.value.trim() !== "") {
    const pRaw = unitPriceInput.value.trim();
    if (!/^\d+(\.\d{1,2})?$/.test(pRaw) || parseFloat(pRaw) <= 0) {
      invalid.push("unit selling price (more than Rs. 0.00)");
      if (firstEmptyField === null) firstEmptyField = unitPriceInput;
    }
  }

  const threshInput = form.querySelector('[name="reorder_threshold"]');
  if (threshInput && threshInput.value.trim() !== "") {
    const tRaw = threshInput.value.trim();
    if (!/^\d+$/.test(tRaw) || parseInt(tRaw, 10) < 0 || parseInt(tRaw, 10) > 10000) {
      invalid.push("reorder threshold (0 to 10,000)");
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
      invalid.push("expiry date");
      if (firstEmptyField === null) firstEmptyField = expiryInput;
    } else {
      const dt = new Date(y, m - 1, d);
      const isRealDate = dt.getFullYear() === y && (dt.getMonth() + 1) === m && dt.getDate() === d;
      if (!isRealDate) {
        invalid.push("expiry date");
        if (firstEmptyField === null) firstEmptyField = expiryInput;
      } else {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (dt <= today) {
          invalid.push("expiry date (must be after today)");
          if (firstEmptyField === null) firstEmptyField = expiryInput;
        }
      }
    }
  }

  const brand = form.querySelector('[name="commercial_name"]').value.trim().toLowerCase();
  const unitForm = form.querySelector('[name="unit_form"]').value.toLowerCase();
  const isKnownMedicine = batchMedicines().some(
    (m) => (m.commercial_name || "").toLowerCase() === brand && (m.unit_form || "").toLowerCase() === unitForm
  );
  const hasCost = costInput && parseFloat(costInput.value) > 0;
  if (brand !== "" && !isKnownMedicine && unitPriceInput.value.trim() === "" && !hasCost) {
    invalid.push("unit selling price for this new medicine (or a total cost to work it out from)");
    if (firstEmptyField === null) firstEmptyField = unitPriceInput;
  }

  if (missing.length > 0 || invalid.length > 0) {
    event.preventDefault();
    const messages = [];
    if (missing.length > 0) messages.push("Please fill in: " + missing.join(", ") + ".");
    if (invalid.length > 0) messages.push("Please enter a valid " + invalid.join(", ") + ".");
    showBatchError(messages.join(" "));
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
    showBatchError("Batch number " + batchCode + " already exists. Batch numbers must be unique.");
    batchInput.focus();
    return;
  }

  document.getElementById("batch-form-error").hidden = true;
}

function batchMedicines() {
  const el = document.getElementById("medicines-data");
  if (!el) return [];
  try {
    return JSON.parse(el.textContent);
  } catch (_) {
    return [];
  }
}

function showBatchError(message) {
  const errorEl = document.getElementById("batch-form-error");
  errorEl.textContent = message;
  errorEl.hidden = false;
}
