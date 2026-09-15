function showScheduleView(name) {
  document.querySelectorAll("[data-view-panel]").forEach((panel) => {
    panel.hidden = panel.getAttribute("data-view-panel") !== name;
  });
  document.querySelectorAll("[data-view-tabs] [data-view]").forEach((tab) => {
    tab.classList.toggle("is-active", tab.getAttribute("data-view") === name);
  });
  document.querySelectorAll(".cal-controls [data-when]").forEach((control) => {
    control.hidden = !control.getAttribute("data-when").includes(name);
  });
  window.scrollTo({ top: 0, behavior: "smooth" });
}

document.querySelectorAll("[data-view-tabs] [data-view]").forEach((tab) => {
  tab.addEventListener("click", () =>
    showScheduleView(tab.getAttribute("data-view")),
  );
});

document.addEventListener("supporting:doctor", (event) => {
  const code = event.detail.code;
  const showAll = code === "all";

  const allDoctorsBoard = document.querySelector('[data-day-mode="all"]');
  if (allDoctorsBoard) allDoctorsBoard.hidden = !showAll;

  document.querySelectorAll('[data-day-mode="one"]').forEach((list) => {
    list.hidden = showAll || list.getAttribute("data-day-doc") !== code;
  });
  showScheduleView("day");
});

const scheduleDates = [
  "Mon 20 Jul 2026",
  "Tue 21 Jul 2026",
  "Wednesday 22 Jul 2026",
  "Thu 23 Jul 2026",
  "Fri 24 Jul 2026",
];
let scheduleDateIndex = 2;

function showScheduleDate(index) {
  if (index < 0) index = 0;
  if (index > scheduleDates.length - 1) index = scheduleDates.length - 1;
  scheduleDateIndex = index;

  const dateLabel = document.querySelector("[data-date-label]");
  if (dateLabel) dateLabel.textContent = scheduleDates[index];
}

const scheduleDateNav = document.querySelector(".cal-datenav");
if (scheduleDateNav) {
  const arrows = scheduleDateNav.querySelectorAll("button");
  if (arrows[0])
    arrows[0].addEventListener("click", () =>
      showScheduleDate(scheduleDateIndex - 1),
    );
  if (arrows[1])
    arrows[1].addEventListener("click", () =>
      showScheduleDate(scheduleDateIndex + 1),
    );
}

document
  .querySelectorAll(".manager-calendar__cell[data-mday]")
  .forEach((cell) => {
    cell.addEventListener("click", () => {
      document
        .querySelectorAll(".manager-calendar__cell.is-selected")
        .forEach((other) => other.classList.remove("is-selected"));
      cell.classList.add("is-selected");

      const title = document.querySelector("[data-mday-title]");
      if (!title) return;

      let day = "";
      const dayNumber = cell.querySelector(".manager-calendar__daynum");
      if (dayNumber) day = dayNumber.textContent;

      let note = "";
      const dayNote = cell.querySelector(".manager-calendar__daynote");
      if (dayNote) note = dayNote.textContent.replace("today · ", "");

      if (note) {
        title.textContent = day + " Jul - " + note;
      } else {
        title.textContent = day + " Jul";
      }
    });
  });

const monthDoctorCards = document.querySelector("[data-mday-docs]");
if (monthDoctorCards) {
  monthDoctorCards.addEventListener("click", (event) => {
    const card = event.target.closest(".month-day-doc");
    if (!card) return;

    monthDoctorCards.querySelectorAll(".month-day-doc").forEach((other) => {
      other.classList.toggle("is-active", other === card);
    });

    const code = card.getAttribute("data-doc");
    document.querySelectorAll("[data-month-slots]").forEach((slots) => {
      slots.hidden = slots.getAttribute("data-month-slots") !== code;
    });

    const label = document.querySelector("[data-mday-doc-label]");
    if (label)
      label.textContent =
        (card.getAttribute("data-name") || "Doctor") + " - sessions";
  });
}

const weekDoctorSelect = document.querySelector("[data-week-doc]");
if (weekDoctorSelect) {
  weekDoctorSelect.addEventListener("change", () => {
    document.querySelectorAll("[data-week-slots]").forEach((slots) => {
      slots.hidden =
        slots.getAttribute("data-week-slots") !== weekDoctorSelect.value;
    });
  });
}

document.querySelectorAll(".week-day").forEach((day) => {
  day.addEventListener("click", () => {
    document
      .querySelectorAll(".week-day.is-selected")
      .forEach((other) => other.classList.remove("is-selected"));
    day.classList.add("is-selected");

    const title = document.querySelector("[data-week-title]");
    const dayName = day.querySelector(".week-day__dow");
    const date = day.querySelector(".week-day__date");
    if (!title || !date) return;

    if (dayName) {
      title.textContent = dayName.textContent + " " + date.textContent;
    } else {
      title.textContent = date.textContent;
    }
  });
});
