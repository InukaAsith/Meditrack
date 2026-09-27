

let inventoryFilter = "all";
let inventorySortKey = "";
let inventorySortDirection = 1;

document.addEventListener("DOMContentLoaded", () => {
  const searchBox = document.getElementById("inventory-search");
  if (!searchBox) return;

  searchBox.addEventListener("input", filterInventory);
  setUpStatusPills();
  setUpSorting();
  setUpSettingsRows();
  filterInventory();
});

function rowStatusGroup(row) {
  const status = row.querySelector("[data-status-badge]").textContent.toLowerCase();
  if (status.includes("low")) return "low";
  if (status.includes("expir")) return "expiring";
  if (status.includes("out")) return "out";
  return "ok";
}

function filterInventory() {
  const query = document.getElementById("inventory-search").value.trim().toLowerCase();
  let shownCount = 0;

  document.querySelectorAll("[data-medicine-row]").forEach((row) => {
    const matchesText = row.getAttribute("data-search").includes(query);
    const matchesPill = inventoryFilter === "all" || rowStatusGroup(row) === inventoryFilter;
    row.hidden = !(matchesText && matchesPill);

    if (row.hidden) settingsRowFor(row).hidden = true;
    if (!row.hidden) shownCount++;
  });

  document.getElementById("inventory-empty").hidden = shownCount !== 0;
}

function setUpStatusPills() {
  const pills = document.querySelectorAll(".staff-pill[data-filter]");
  pills.forEach((pill) => {
    pill.addEventListener("click", () => {
      pills.forEach((other) => other.classList.toggle("is-active", other === pill));
      inventoryFilter = pill.getAttribute("data-filter");
      filterInventory();
    });
  });

  const activePill = document.querySelector(".staff-pill.is-active[data-filter]");
  if (activePill) inventoryFilter = activePill.getAttribute("data-filter");
}

function sortValue(row, key) {
  if (key === "stock") return parseInt(row.getAttribute("data-stock"), 10);
  if (key === "expiry") return Date.parse(row.getAttribute("data-expiry")) || 0;
  return row.getAttribute("data-search");
}

function setUpSorting() {
  document.querySelectorAll("[data-sort]").forEach((button) => {
    button.addEventListener("click", () => {
      const key = button.getAttribute("data-sort");
      if (inventorySortKey === key) {
        inventorySortDirection = -inventorySortDirection;
      } else {
        inventorySortKey = key;
        inventorySortDirection = 1;
      }
      sortInventory(key);
    });
  });
}

function sortInventory(key) {
  const tableBody = document.getElementById("inventory-body");
  const rows = Array.from(tableBody.querySelectorAll("[data-medicine-row]"));

  rows.sort((a, b) => {
    const valueA = sortValue(a, key);
    const valueB = sortValue(b, key);
    if (valueA < valueB) return -inventorySortDirection;
    if (valueA > valueB) return inventorySortDirection;
    return 0;
  });

  for (const row of rows) {
    const settingsRow = settingsRowFor(row);
    tableBody.appendChild(row);
    tableBody.appendChild(settingsRow);
  }
}

function settingsRowFor(row) {
  const id = row.getAttribute("data-medicine-row");
  return document.querySelector('[data-settings-row="' + id + '"]');
}

function medicineRowFor(settingsRow) {
  const id = settingsRow.getAttribute("data-settings-row");
  return document.querySelector('[data-medicine-row="' + id + '"]');
}

function showSettingsError(settingsRow, message) {
  const errorLine = settingsRow.querySelector("[data-settings-error]");
  errorLine.textContent = message;
  errorLine.hidden = message === "";
}

function updateMedicineRow(row, totalStock, status, tone) {
  row.setAttribute("data-stock", totalStock);
  row.querySelector("[data-stock-number]").textContent = totalStock.toLocaleString();

  const badge = row.querySelector("[data-status-badge]");
  badge.className = "badge badge--" + tone;
  badge.textContent = status;
}

async function postInventory(url, data) {
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
  data.csrf_token = token;

  try {
    const response = await fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "X-CSRF-Token": token,
      },
      body: JSON.stringify(data),
    });
    return await response.json();
  } catch (error) {
    return { ok: false, error: "Could not reach the server. Check the connection and try again." };
  }
}

function setUpSettingsRows() {
  document.querySelectorAll("[data-settings-toggle]").forEach((button) => {
    button.addEventListener("click", () => {
      const settingsRow = settingsRowFor(button.closest("[data-medicine-row]"));
      settingsRow.hidden = !settingsRow.hidden;
    });
  });

  document.querySelectorAll("[data-settings-save]").forEach((button) => {
    button.addEventListener("click", () => saveSettings(button));
  });

  document.querySelectorAll("[data-adjust-apply]").forEach((button) => {
    button.addEventListener("click", () => applyAdjustment(button));
  });

  document.querySelectorAll("[data-batch]").forEach(setUpBatchRemove);
}

async function saveSettings(button) {
  const settingsRow = button.closest("[data-settings-row]");
  const row = medicineRowFor(settingsRow);

  const thresholdRaw = settingsRow.querySelector('[data-setting="threshold"]').value.trim();
  const threshold = parseInt(thresholdRaw, 10);
  if (isNaN(threshold) || threshold < 0) {
    showSettingsError(settingsRow, "Reorder threshold cannot be negative.");
    window.showAlertDialog({ title: "Invalid Threshold", message: "Reorder threshold cannot be negative." });
    return;
  }
  if (threshold > 10000) {
    showSettingsError(settingsRow, "Reorder threshold cannot exceed 10,000 units.");
    window.showAlertDialog({ title: "Invalid Threshold", message: "Reorder threshold cannot exceed 10,000 units." });
    return;
  }

  const priceRaw = settingsRow.querySelector('[data-setting="price"]').value.trim();
  const price = parseFloat(priceRaw);
  if (isNaN(price) || price <= 0) {
    showSettingsError(settingsRow, "Unit price must be greater than Rs. 0.00.");
    window.showAlertDialog({ title: "Invalid Price", message: "Unit price must be greater than Rs. 0.00." });
    return;
  }
  if (price > 1000000) {
    showSettingsError(settingsRow, "Unit price cannot exceed Rs. 1,000,000.00.");
    window.showAlertDialog({ title: "Invalid Price", message: "Unit price cannot exceed Rs. 1,000,000.00." });
    return;
  }

  const markedOut = settingsRow.querySelector('[data-setting="out-of-stock"]').checked;

  button.disabled = true;
  const result = await postInventory("/staff/pharmacist/inventory-config", {
    medicine_id: parseInt(button.getAttribute("data-settings-save"), 10),
    threshold: threshold,
    price: price,
    requires_rx: settingsRow.querySelector('[data-setting="requires-rx"]').checked,
    damaged_override: markedOut,
  });
  button.disabled = false;

  if (!result.ok) {
    showSettingsError(settingsRow, result.error);
    window.showAlertDialog({ title: "Save Settings Error", message: result.error });
    return;
  }
  showSettingsError(settingsRow, "");
  row.querySelector("[data-reorder-number]").textContent = threshold;

  const stock = parseInt(row.getAttribute("data-stock"), 10);
  if (markedOut) {
    updateMedicineRow(row, stock, "Marked out of stock", "muted");
  } else if (stock === 0) {
    updateMedicineRow(row, stock, "Out of stock", "muted");
  } else if (stock <= threshold) {
    updateMedicineRow(row, stock, "Low stock", "danger");
  } else {
    updateMedicineRow(row, stock, "In stock", "success");
  }
}

async function applyAdjustment(button) {
  const settingsRow = button.closest("[data-settings-row]");
  const quantityInput = settingsRow.querySelector('[data-adjust="quantity"]');
  const reasonSelect = settingsRow.querySelector('[data-adjust="reason"]');
  const batchInput = settingsRow.querySelector('[data-adjust="batch"]');

  const qtyRaw = quantityInput.value.trim();
  if (!/^-?\d+$/.test(qtyRaw)) {
    showSettingsError(settingsRow, "Enter a valid numeric quantity (whole numbers only).");
    window.showAlertDialog({ title: "Invalid Quantity", message: "Enter a valid numeric quantity (whole numbers only)." });
    return;
  }
  const quantity = parseInt(qtyRaw, 10);
  if (quantity === 0) {
    showSettingsError(settingsRow, "Enter how many units to add or remove (cannot be 0).");
    window.showAlertDialog({ title: "Invalid Quantity", message: "Enter how many units to add or remove (cannot be 0)." });
    return;
  }

  let batchId = null;
  if (batchInput) batchId = parseInt(batchInput.value, 10);

  button.disabled = true;
  const result = await postInventory("/staff/pharmacist/inventory-adjust", {
    medicine_id: parseInt(button.getAttribute("data-adjust-apply"), 10),
    batch_id: batchId,
    quantity: quantity,
    reason: reasonSelect.value,
    note: reasonSelect.options[reasonSelect.selectedIndex].text,
  });
  button.disabled = false;

  if (!result.ok) {
    showSettingsError(settingsRow, result.error);
    window.showAlertDialog({ title: "Stock Adjustment Error", message: result.error });
    return;
  }
  showSettingsError(settingsRow, "");
  quantityInput.value = "0";

  const data = result.data;
  updateMedicineRow(medicineRowFor(settingsRow), data.total_stock, data.status, data.status_tone);

  const batchRow = settingsRow.querySelector('[data-batch="' + data.batch_id + '"]');
  if (batchRow) batchRow.querySelector("[data-batch-units]").textContent = data.new_batch_stock;
}

function setUpBatchRemove(batchRow) {
  const removeButton = batchRow.querySelector("[data-batch-remove]");
  if (!removeButton) return;

  const batchCode = batchRow.querySelector(".morning-row__name")?.textContent.trim() || "this batch";
  const batchUnits = batchRow.querySelector("[data-batch-units]")?.textContent.trim() || "0";
  const batchId = parseInt(batchRow.getAttribute("data-batch"), 10);

  removeButton.addEventListener("click", () => {
    window.showConfirmDialog({
      title: "Remove Batch",
      message: `Are you sure you want to remove batch <strong>${batchCode}</strong> (${batchUnits} units)? Remaining units will be marked as removed/damaged.`,
      confirmText: "Remove Batch",
      cancelText: "Keep Batch",
      danger: true,
      onConfirm: async () => {
        const settingsRow = batchRow.closest("[data-settings-row]");
        removeButton.disabled = true;

        const result = await postInventory("/staff/pharmacist/inventory-batch-remove", {
          batch_id: batchId,
        });
        removeButton.disabled = false;

        if (!result.ok) {
          showSettingsError(settingsRow, result.error);
          window.showAlertDialog({
            title: "Cannot Remove Batch",
            message: result.error
          });
          return;
        }
        showSettingsError(settingsRow, "");

        batchRow.querySelector("[data-batch-units]").textContent = "0";
        batchRow.querySelector("[data-batch-actions]").hidden = true;
        batchRow.querySelector("[data-batch-removed]").hidden = false;

        const data = result.data;
        updateMedicineRow(medicineRowFor(settingsRow), data.total_stock, data.status, data.status_tone);
      }
    });
  });
}
