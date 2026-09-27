let photoZoom = 1;
let photoAngle = 0;

document.addEventListener("DOMContentLoaded", () => {
  setUpScan();
  setUpMedicineRows();
  setUpMedicineSearch();
  setUpPayButtons();
  setUpPhotoTools();
});

function formatAmount(amount) {
  return amount.toLocaleString("en-LK");
}

function showScannedPatient() {
  document.getElementById("scan-result").hidden = false;
  document.getElementById("scan-side-hint").hidden = true;
  document.getElementById("scan-side-list").hidden = false;
}

function setUpScan() {
  const scanForm = document.getElementById("scan-form");
  if (!scanForm) return;

  scanForm.addEventListener("submit", (event) => {
    event.preventDefault();
    showScannedPatient();
  });
  document.getElementById("scan-demo").addEventListener("click", showScannedPatient);
}

function shownMedicineRows() {
  const shown = [];
  document.querySelectorAll("tr[data-medicine]").forEach((row) => {
    if (!row.hidden) shown.push(row);
  });
  return shown;
}

function setRowQuantity(row, quantity) {
  const unitPrice = parseInt(row.getAttribute("data-unit-price"), 10);
  row.querySelector("[data-qty]").textContent = quantity;
  row.querySelector("[data-amount]").textContent = formatAmount(quantity * unitPrice);
}

function updateDispenseTotal() {
  const rows = shownMedicineRows();
  let total = 0;
  for (const row of rows) {
    const quantity = parseInt(row.querySelector("[data-qty]").textContent, 10);
    total += quantity * parseInt(row.getAttribute("data-unit-price"), 10);
  }

  if (rows.length === 1) {
    document.getElementById("dispense-count").textContent = "1 medicine";
  } else {
    document.getElementById("dispense-count").textContent = rows.length + " medicines";
  }
  document.getElementById("dispense-total").textContent = "Rs. " + formatAmount(total);
  document.getElementById("dispense-empty").hidden = rows.length !== 0;
}

function setUpMedicineRows() {
  document.querySelectorAll("tr[data-medicine]").forEach((row) => {
    row.querySelectorAll("[data-step]").forEach((button) => {
      button.addEventListener("click", () => {
        const quantity = parseInt(row.querySelector("[data-qty]").textContent, 10);
        const step = parseInt(button.getAttribute("data-step"), 10);
        setRowQuantity(row, Math.max(1, quantity + step));
        updateDispenseTotal();
      });
    });

    row.querySelector("[data-remove]").addEventListener("click", () => {
      row.hidden = true;
      updateDispenseTotal();
    });
  });
}

function showAddError(message) {
  const errorLine = document.getElementById("dispense-add-error");
  errorLine.textContent = message;
  errorLine.hidden = message === "";
}

function addMedicine(name) {
  const row = document.querySelector('tr[data-medicine="' + name + '"]');
  if (!row) {
    showAddError("That medicine is not in the pharmacy's list.");
    return;
  }
  if (!row.hidden) {
    showAddError("That medicine is already on the list.");
    return;
  }

  showAddError("");
  setRowQuantity(row, parseInt(row.getAttribute("data-default-qty"), 10));
  row.hidden = false;
  updateDispenseTotal();

  document.getElementById("dispense-search").value = "";
  document.getElementById("dispense-results").hidden = true;
}

function showMatchingResults(text) {
  const query = text.trim().toLowerCase();
  const words = query.split(/\s+/).filter(Boolean);
  let matchCount = 0;

  document.querySelectorAll("[data-result]").forEach((result) => {
    const resText = (result.getAttribute("data-result") || "").toLowerCase();
    const matches = words.length > 0 && words.every((w) => resText.includes(w));
    result.hidden = !matches;
    if (matches) matchCount++;
  });

  document.getElementById("dispense-results").hidden = matchCount === 0;
}

function setUpMedicineSearch() {
  const searchBox = document.getElementById("dispense-search");
  if (!searchBox) return;
  const results = document.getElementById("dispense-results");

  searchBox.addEventListener("input", () => showMatchingResults(searchBox.value));

  searchBox.addEventListener("blur", () => {
    setTimeout(() => {
      results.hidden = true;
    }, 150);
  });

  document.querySelectorAll("[data-result]").forEach((result) => {
    result.addEventListener("mousedown", () => addMedicine(result.getAttribute("data-result")));
  });

  document.getElementById("dispense-add").addEventListener("click", () => {
    const name = searchBox.value.trim().toLowerCase();
    if (name === "") {
      showAddError("Type a medicine name to add.");
      return;
    }
    addMedicine(name);
  });
}

function setUpPayButtons() {
  const group = document.querySelector("[data-pay-buttons]");
  if (!group) return;

  const buttons = group.querySelectorAll(".staff-pill");
  buttons.forEach((button) => {
    button.addEventListener("click", () => {
      buttons.forEach((other) => other.classList.toggle("is-active", other === button));
    });
  });
}

function setUpPhotoTools() {
  const photo = document.getElementById("dispense-photo");
  if (!photo) return;

  document.querySelectorAll("[data-photo]").forEach((button) => {
    button.addEventListener("click", () => {
      const action = button.getAttribute("data-photo");
      if (action === "zoom-in") {
        photoZoom = Math.min(photoZoom + 0.2, 2.4);
      } else if (action === "zoom-out") {
        photoZoom = Math.max(photoZoom - 0.2, 0.6);
      } else if (action === "rotate") {
        photoAngle = photoAngle + 90;
      } else {
        photoZoom = 1;
        photoAngle = 0;
      }
      photo.style.transform = "scale(" + photoZoom + ") rotate(" + photoAngle + "deg)";
    });
  });
}
