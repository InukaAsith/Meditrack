function money(n) {
  return "Rs. " + Number(n).toLocaleString("en-LK");
}

setUpOtcBasket();
setUpOrderBuilder();

function setUpOtcBasket() {
  const otcSummary = document.getElementById("otc-summary");
  const otcInput = document.getElementById("otc-search-input");
  if (!otcSummary || !otcInput) return;

  const results = document.getElementById("otc-results");
  const resultItems = document.querySelectorAll("[data-otc-result]");
  const basketRows = document.querySelectorAll("[data-otc-row]");
  const buyBtn = document.getElementById("otc-buy");
  const basket = {};

  function renderBasket() {
    let total = 0;
    let itemCount = 0;

    for (const row of basketRows) {
      const qty = basket[row.getAttribute("data-otc-row")] || 0;
      const amount = qty * Number(row.getAttribute("data-price"));
      row.hidden = qty === 0;
      row.querySelector("[data-otc-qty]").textContent = qty;
      row.querySelector("[data-otc-amount]").textContent = money(amount);
      total += amount;
      itemCount += qty;
    }

    const hasItems = itemCount > 0;
    document.getElementById("otc-table").hidden = !hasItems;
    document.getElementById("otc-empty").hidden = hasItems;
    buyBtn.disabled = !hasItems;

    let countText = itemCount + " items";
    if (itemCount === 1) countText = "1 item";
    otcSummary.querySelector("[data-otc-count]").textContent = countText;
    otcSummary.querySelector("[data-otc-total]").textContent = money(total);
    otcSummary.querySelector("[data-otc-grand]").textContent = money(total);
  }

  function changeQty(id, change) {
    const qty = (basket[id] || 0) + change;
    if (qty > 0) {
      basket[id] = qty;
    } else {
      delete basket[id];
    }
    renderBasket();
  }

  for (const row of basketRows) {
    const id = row.getAttribute("data-otc-row");
    const stepButtons = row.querySelectorAll("[data-step]");
    for (const btn of stepButtons) {
      btn.addEventListener("click", () => {
        changeQty(id, parseInt(btn.getAttribute("data-step"), 10));
      });
    }
    row.querySelector("[data-otc-remove]").addEventListener("click", () => {
      delete basket[id];
      renderBasket();
    });
  }

  function showResults() {
    const typed = otcInput.value.trim();
    if (typed === "") {
      results.hidden = true;
      return;
    }

    let matchCount = 0;
    for (const item of resultItems) {
      const matches = item
        .getAttribute("data-name")
        .includes(typed.toLowerCase());
      item.hidden = !matches;
      if (matches) matchCount++;
    }

    document.getElementById("otc-no-match-text").textContent = typed;
    document.getElementById("otc-no-match").hidden = matchCount > 0;
    results.hidden = false;
  }

  for (const item of resultItems) {
    item.querySelector("button").addEventListener("click", () => {
      changeQty(item.getAttribute("data-otc-result"), 1);
      otcInput.value = "";
      results.hidden = true;
      otcInput.focus();
    });
  }

  otcInput.addEventListener("input", showResults);
  otcInput.addEventListener("focus", showResults);
  document.addEventListener("click", (e) => {
    if (!results.contains(e.target) && e.target !== otcInput) {
      results.hidden = true;
    }
  });

  const payOptions = otcSummary.querySelectorAll("[data-otcpay]");
  for (const opt of payOptions) {
    opt.addEventListener("click", () => {
      for (const other of payOptions) {
        other.classList.toggle("is-active", other === opt);
      }
    });
  }

  renderBasket();
}

function setUpOrderBuilder() {
  const table = document.getElementById("rx-table");
  const summary = document.getElementById("rx-summary");
  if (!table || !summary) return;

  const rows = Array.from(table.querySelectorAll("[data-rx-row]"));

  function rowState(row) {
    const perDay = parseFloat(row.getAttribute("data-per-day")) || 0;
    const days = parseInt(row.getAttribute("data-days"), 10) || 0;
    const check = row.querySelector("[data-rx-check]");
    return { perDay: perDay, days: days, selected: check && check.checked };
  }

  function recompute() {
    let total = 0;
    let selectedCount = 0;

    for (const row of rows) {
      const s = rowState(row);
      row.querySelector("[data-amount]").textContent = money(s.perDay * s.days);
      row.querySelector("[data-days-val]").textContent = s.days + " days";
      row.classList.toggle("is-off", !s.selected);
      if (s.selected) {
        total += s.perDay * s.days;
        selectedCount++;
      }
    }

    summary.querySelector("[data-summary-count]").textContent =
      "Selected (" + selectedCount + " of " + rows.length + ")";
    summary.querySelector("[data-summary-total]").textContent = money(total);
    summary.querySelector("[data-summary-grand]").textContent = money(total);
  }

  for (const row of rows) {
    const check = row.querySelector("[data-rx-check]");
    if (check) check.addEventListener("change", recompute);

    const stepButtons = row.querySelectorAll("[data-step]");
    for (const btn of stepButtons) {
      btn.addEventListener("click", () => {
        let days = parseInt(row.getAttribute("data-days"), 10) || 0;
        days = Math.max(1, days + parseInt(btn.getAttribute("data-step"), 10));
        row.setAttribute("data-days", String(days));
        recompute();
      });
    }
  }

  const payOptions = summary.querySelectorAll("[data-pay]");
  for (const opt of payOptions) {
    opt.addEventListener("click", () => {
      for (const other of payOptions) {
        other.classList.toggle("is-active", other === opt);
      }
    });
  }

  recompute();
}
