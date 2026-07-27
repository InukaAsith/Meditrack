function showCalendarMonth(calendar, index) {
  const months = calendar.querySelectorAll("[data-month]");
  if (index < 0 || index >= months.length) return;

  months.forEach((month, i) => {
    month.hidden = i !== index;
  });

  const label = calendar.querySelector("[data-month-label]");
  if (label)
    label.textContent = months[index].getAttribute("data-month-label") || "";

  const prevButton = calendar.querySelector("[data-month-prev]");
  const nextButton = calendar.querySelector("[data-month-next]");
  if (prevButton) prevButton.disabled = index === 0;
  if (nextButton) nextButton.disabled = index === months.length - 1;
}

function visibleMonthIndex(calendar) {
  const months = calendar.querySelectorAll("[data-month]");
  for (let i = 0; i < months.length; i++) {
    if (!months[i].hidden) return i;
  }
  return 0;
}

function clearCalendarSelection(calendar) {
  calendar.querySelectorAll("[data-day]").forEach((cell) => {
    cell.classList.remove("is-selected");
  });
}

function selectCalendarDay(calendar, day) {
  const cell = calendar.querySelector('[data-day="' + day + '"]');
  if (!cell || cell.disabled) return false;

  const months = Array.from(calendar.querySelectorAll("[data-month]"));
  const monthIndex = months.indexOf(cell.closest("[data-month]"));
  if (monthIndex !== -1) showCalendarMonth(calendar, monthIndex);

  clearCalendarSelection(calendar);
  cell.classList.add("is-selected");
  return true;
}

function setCalendarDayLabels(calendar, labels) {
  calendar.querySelectorAll("[data-day]").forEach((cell) => {
    const day = cell.getAttribute("data-day");
    const hasLabel = labels.hasOwnProperty(day);

    const sublabel = cell.querySelector(".month-cal__sublabel");
    if (sublabel) {
      if (hasLabel) {
        sublabel.textContent = labels[day];
      } else {
        sublabel.textContent = "";
      }
    }

    cell.disabled = !hasLabel;
    cell.classList.toggle("is-disabled", !hasLabel);
    if (!hasLabel) cell.classList.remove("is-selected");
  });
}

document.querySelectorAll("[data-month-cal]").forEach((calendar) => {
  const prevButton = calendar.querySelector("[data-month-prev]");
  const nextButton = calendar.querySelector("[data-month-next]");
  if (prevButton) {
    prevButton.addEventListener("click", () =>
      showCalendarMonth(calendar, visibleMonthIndex(calendar) - 1),
    );
  }
  if (nextButton) {
    nextButton.addEventListener("click", () =>
      showCalendarMonth(calendar, visibleMonthIndex(calendar) + 1),
    );
  }

  calendar.addEventListener("click", (event) => {
    const cell = event.target.closest("[data-day]");
    if (!cell || cell.disabled) return;

    clearCalendarSelection(calendar);
    cell.classList.add("is-selected");

    const day = cell.getAttribute("data-day");
    calendar.dispatchEvent(
      new CustomEvent("calendar-day-picked", { detail: { day: day } }),
    );
  });

  showCalendarMonth(calendar, visibleMonthIndex(calendar));
});
