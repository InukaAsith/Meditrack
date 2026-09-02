const pageParams = new URLSearchParams(location.search);
const calendarDateLabel = document.querySelector("[data-date-label]");
const singleDoctorTitle = document.querySelector("[data-single-title]");
const apptMessageBoard = document.getElementById("appt-msgboard");
const apptMessageEmpty = document.getElementById("appt-msg-empty");
const monthDayDoctorLabel = document.querySelector("[data-mday-doc-label]");
const monthDayDoctors = document.querySelector("[data-mday-docs]");

let selectedDoctor = "all";
let monthDayShort = "Fri 24 Jul";

function showCalendarView(name) {
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
    showCalendarView(tab.getAttribute("data-view")),
  );
});

function selectDoctor(code) {
  document.querySelectorAll(".doc-rail__item").forEach((item) => {
    item.classList.toggle("is-active", item.getAttribute("data-doc") === code);
  });

  let mode = "one";
  if (code === "all") mode = "all";
  document.querySelectorAll("[data-day-mode]").forEach((section) => {
    section.hidden = section.getAttribute("data-day-mode") !== mode;
  });
  if (mode === "all") return;

  selectedDoctor = code || "all";

  let doctorName = "";
  const nameElement = document.querySelector(
    '.doc-rail__item[data-doc="' + code + '"] .doc-rail__name',
  );
  if (nameElement) doctorName = nameElement.textContent;

  if (doctorName) {
    let dateText = "Friday 24 Jul 2026";
    if (calendarDateLabel) dateText = calendarDateLabel.textContent;
    if (singleDoctorTitle)
      singleDoctorTitle.textContent = doctorName + " on " + dateText;

    const singleDoctorName = document.querySelector("[data-single-doc]");
    if (singleDoctorName) singleDoctorName.textContent = doctorName;
  }
  filterApptMessageBoard();
}

document.querySelectorAll(".doc-rail__item").forEach((item) => {
  item.addEventListener("click", () =>
    selectDoctor(item.getAttribute("data-doc")),
  );
});

document.querySelectorAll("[data-day-col]").forEach((column) => {
  column.addEventListener("click", () => openDoctorColumn(column));
  column.addEventListener("keydown", (event) => {
    if (event.key === "Enter" || event.key === " ") {
      event.preventDefault();
      openDoctorColumn(column);
    }
  });
});

function openDoctorColumn(column) {
  selectDoctor(column.getAttribute("data-day-col"));
  window.scrollTo({ top: 0, behavior: "smooth" });
}

const statusFilters = document.querySelector("[data-appt-filters]");

if (statusFilters) {
  const pills = statusFilters.querySelectorAll(".staff-pill");
  pills.forEach((pill) => {
    pill.addEventListener("click", () => {
      pills.forEach((other) =>
        other.classList.toggle("is-active", other === pill),
      );
      filterQueueByStatus(pill.textContent.trim().toLowerCase());
    });
  });
}

function filterQueueByStatus(filter) {
  document
    .querySelectorAll('[data-day-mode="one"] [data-queue-row]')
    .forEach((row) => {
      const badge = row.querySelector(".badge");
      let status = "";
      if (badge) status = badge.textContent.toLowerCase();

      let show = true;
      if (filter === "upcoming") {
        show =
          status.includes("not arrived") ||
          status.includes("rescheduled") ||
          status.includes("in queue") ||
          status.includes("ready");
      } else if (filter === "cancelled") {
        show = status.includes("cancelled");
      } else if (filter === "no-shows") {
        show =
          /no.?show/.test(status) ||
          row.querySelector(".icon-act--flag") !== null;
      }
      row.hidden = !show;
    });
}

if (monthDayDoctors) {
  monthDayDoctors.addEventListener("change", () => {
    const option = monthDayDoctors.options[monthDayDoctors.selectedIndex];

    const fullDayLink = document.querySelector("[data-mday-fullday]");
    if (fullDayLink) {
      fullDayLink.setAttribute(
        "href",
        "/staff/receptionist/appointments?view=day&doc=" + option.value,
      );
    }
    if (monthDayDoctorLabel) {
      monthDayDoctorLabel.textContent =
        option.getAttribute("data-name") + " on " + monthDayShort;
    }
  });
}

function filterApptMessageBoard() {
  if (!apptMessageBoard) return;
  let shownCount = 0;

  apptMessageBoard.querySelectorAll("[data-doc]").forEach((post) => {
    const postDoctor = post.getAttribute("data-doc");
    const show =
      selectedDoctor === "all" ||
      postDoctor === "all" ||
      postDoctor === selectedDoctor;
    post.hidden = !show;
    if (show) shownCount++;
  });

  if (apptMessageEmpty) apptMessageEmpty.hidden = shownCount !== 0;
}

const apptMessageInput = document.getElementById("appt-msg-input");
const apptMessagePost = document.getElementById("appt-msg-post");

function postApptMessage() {
  if (!apptMessageInput || !apptMessageBoard) return;
  const text = apptMessageInput.value.trim();
  if (!text) return;

  const now = new Date();
  const time =
    String(now.getHours()).padStart(2, "0") +
    ":" +
    String(now.getMinutes()).padStart(2, "0");

  const template = document.getElementById("appt-msg-template");
  const post = template.content.firstElementChild.cloneNode(true);
  post.setAttribute("data-doc", selectedDoctor);
  post.querySelector("p").textContent = text;
  post.querySelector(".message-board__time").textContent = time;

  if (apptMessageEmpty) {
    apptMessageBoard.insertBefore(post, apptMessageEmpty);
  } else {
    apptMessageBoard.appendChild(post);
  }
  apptMessageInput.value = "";
  filterApptMessageBoard();
}

if (apptMessagePost) apptMessagePost.addEventListener("click", postApptMessage);
if (apptMessageInput) {
  apptMessageInput.addEventListener("keydown", (event) => {
    if (event.key === "Enter") {
      event.preventDefault();
      postApptMessage();
    }
  });
}

const pausedBanner = document.getElementById("queue-paused");

if (pausedBanner && pageParams.get("paused") === "1") {
  const bannerName = document.getElementById("qp-name");
  const bannerDoctor = document.getElementById("qp-doc");
  if (bannerName)
    bannerName.textContent = pageParams.get("name") || "the patient";
  if (bannerDoctor)
    bannerDoctor.textContent = pageParams.get("with") || "the on-call doctor";
  pausedBanner.hidden = false;
}

document.querySelectorAll("[data-emergency-resume]").forEach((button) => {
  button.addEventListener("click", () => {
    if (pausedBanner) pausedBanner.hidden = true;
  });
});

const calendarDates = [
  "Wed 22 Jul 2026",
  "Thu 23 Jul 2026",
  "Friday 24 Jul 2026",
  "Sat 25 Jul 2026",
  "Mon 27 Jul 2026",
];
let calendarDateIndex = 2;

function showCalendarDate(index) {
  if (index < 0) index = 0;
  if (index > calendarDates.length - 1) index = calendarDates.length - 1;
  calendarDateIndex = index;

  if (calendarDateLabel) calendarDateLabel.textContent = calendarDates[index];

  if (singleDoctorTitle && selectedDoctor !== "all") {
    const nameElement = document.querySelector(
      '.doc-rail__item[data-doc="' + selectedDoctor + '"] .doc-rail__name',
    );
    if (nameElement)
      singleDoctorTitle.textContent =
        nameElement.textContent + " on " + calendarDates[index];
  }
}

const calendarDateNav = document.querySelector(".cal-datenav");
if (calendarDateNav) {
  const arrows = calendarDateNav.querySelectorAll("button");
  if (arrows[0])
    arrows[0].addEventListener("click", () =>
      showCalendarDate(calendarDateIndex - 1),
    );
  if (arrows[1])
    arrows[1].addEventListener("click", () =>
      showCalendarDate(calendarDateIndex + 1),
    );
}

document.querySelectorAll(".week-day").forEach((day) => {
  if (day.classList.contains("is-closed")) return;
  day.style.cursor = "pointer";
  day.addEventListener("click", () => selectWeekDay(day));
});

function selectWeekDay(day) {
  document
    .querySelectorAll(".week-day.is-selected")
    .forEach((other) => other.classList.remove("is-selected"));
  day.classList.add("is-selected");

  const title = document.querySelector("[data-week-queue-title]");
  const dayName = day.querySelector(".week-day__dow");
  const date = day.querySelector(".week-day__date");
  const count = day.querySelector(".week-day__count");
  if (title && dayName && date) {
    let text =
      "Dr. Sample Doctor 1 on " + dayName.textContent + " " + date.textContent;
    if (count) {
      text += " (" + count.textContent.trim() + ")";
    }
    title.textContent = text;
  }

  const weekQueue = document.querySelector(".week-queue");
  if (weekQueue)
    weekQueue.scrollIntoView({ behavior: "smooth", block: "nearest" });
}

document
  .querySelectorAll(".manager-calendar__cell[data-mday]")
  .forEach((cell) => {
    cell.addEventListener("click", () => selectMonthDay(cell));
  });

function selectMonthDay(cell) {
  document
    .querySelectorAll(".manager-calendar__cell.is-selected")
    .forEach((other) => other.classList.remove("is-selected"));
  cell.classList.add("is-selected");

  let day = "";
  const dayNumber = cell.querySelector(".manager-calendar__daynum");
  if (dayNumber) day = dayNumber.textContent;

  let note = "";
  const dayNote = cell.querySelector(".manager-calendar__daynote");
  if (dayNote) note = dayNote.textContent;

  monthDayShort = day + " Jul";

  const title = document.querySelector("[data-mday-title]");
  if (title) {
    if (note) {
      title.textContent = day + " Jul (" + note + ")";
    } else {
      title.textContent = day + " Jul";
    }
  }

  if (monthDayDoctorLabel) {
    let doctorName = "Dr. Sample Doctor 1";
    if (monthDayDoctors) {
      doctorName =
        monthDayDoctors.options[monthDayDoctors.selectedIndex].getAttribute(
          "data-name",
        );
    }
    monthDayDoctorLabel.textContent = doctorName + " on " + day + " Jul";
  }
}

const deepLinkView = pageParams.get("view");
if (deepLinkView === "week" || deepLinkView === "month")
  showCalendarView(deepLinkView);

const deepLinkDoctor = pageParams.get("doc");
if (deepLinkDoctor) {
  showCalendarView("day");
  selectDoctor(deepLinkDoctor);
}
