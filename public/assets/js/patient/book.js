let entryTab = "doctor";
let chosenDoctor = "1";

document.addEventListener("DOMContentLoaded", () => {
  if (!document.querySelector("[data-book-panel]")) return;

  setUpTabs();
  setUpPickers();
  setUpDoctorSearch();
  setUpDoctorButtons();
  setUpCalendars();
  setUpSessionButtons();
  setUpDetailsStep();

  entryTab = document
    .querySelector("[data-book-tab].is-active")
    .getAttribute("data-book-tab");
});

function showOnly(attribute, value) {
  const elements = document.querySelectorAll("[" + attribute + "]");
  for (const element of elements) {
    element.hidden = element.getAttribute(attribute) !== value;
  }
}

function showPanel(name) {
  showOnly("data-book-panel", name);

  let litTab = entryTab;
  if (name === "doctor" || name === "date") litTab = name;

  const tabs = document.querySelectorAll("[data-book-tab]");
  for (const tab of tabs) {
    tab.classList.toggle(
      "is-active",
      tab.getAttribute("data-book-tab") === litTab,
    );
  }
  window.scrollTo({ top: 0, behavior: "smooth" });
}

function setUpTabs() {
  const tabs = document.querySelectorAll("[data-book-tab]");
  for (const tab of tabs) {
    tab.addEventListener("click", () => {
      entryTab = tab.getAttribute("data-book-tab");
      showPanel(entryTab);
    });
  }

  const backButtons = document.querySelectorAll("[data-book-back]");
  for (const backButton of backButtons) {
    backButton.addEventListener("click", () => {
      const target = backButton.getAttribute("data-book-back");
      if (target) {
        showPanel(target);
      } else {
        showPanel(entryTab);
      }
    });
  }
}

function filterPickerOptions(picker) {
  const query = picker
    .querySelector("[data-picker-search]")
    .value.toLowerCase()
    .trim();

  let shown = 0;
  const rows = picker.querySelectorAll("[data-option-search]");
  for (const row of rows) {
    const matches =
      query === "" || row.getAttribute("data-option-search").includes(query);
    row.hidden = !matches;
    if (matches) {
      shown = shown + 1;
    }
  }

  picker.querySelector("[data-picker-none]").hidden = shown > 0;
}

function openPicker(picker) {
  const search = picker.querySelector("[data-picker-search]");
  search.value = "";
  filterPickerOptions(picker);
  picker.querySelector("[data-picker-menu]").hidden = false;
  picker
    .querySelector("[data-picker-trigger]")
    .setAttribute("aria-expanded", "true");
  search.focus();
}

function closePicker(picker) {
  picker.querySelector("[data-picker-menu]").hidden = true;
  picker
    .querySelector("[data-picker-trigger]")
    .setAttribute("aria-expanded", "false");
}

function setUpPickers() {
  const pickers = document.querySelectorAll("[data-picker]");

  for (const picker of pickers) {
    const trigger = picker.querySelector("[data-picker-trigger]");

    trigger.addEventListener("click", () => {
      if (picker.querySelector("[data-picker-menu]").hidden) {
        openPicker(picker);
      } else {
        closePicker(picker);
      }
    });

    picker
      .querySelector("[data-picker-search]")
      .addEventListener("input", () => {
        filterPickerOptions(picker);
      });

    const options = picker.querySelectorAll(".search-select__option");
    for (const option of options) {
      option.addEventListener("click", () => {
        for (const other of options) {
          other.classList.toggle("is-active", other === option);
        }
        picker.querySelector("[data-picker-chosen]").textContent =
          option.textContent;
        closePicker(picker);
        trigger.focus();
      });
    }
  }

  document.addEventListener("click", (event) => {
    for (const picker of pickers) {
      if (!picker.contains(event.target)) {
        closePicker(picker);
      }
    }
  });
  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      for (const picker of pickers) {
        closePicker(picker);
      }
    }
  });
}

let activeFilter = "All";

function filterDoctorGrid() {
  const query = document
    .getElementById("doc-search")
    .value.toLowerCase()
    .trim();
  const filter = activeFilter.toLowerCase();

  let shown = 0;
  const cards = document.querySelectorAll("#doc-grid [data-search]");
  for (const card of cards) {
    const searchText = card.getAttribute("data-search");
    const matchesQuery = query === "" || searchText.includes(query);
    const matchesFilter = activeFilter === "All" || searchText.includes(filter);
    card.hidden = !(matchesQuery && matchesFilter);
    if (!card.hidden) {
      shown = shown + 1;
    }
  }

  document.getElementById("doc-none").hidden = shown > 0;
}

function setUpDoctorSearch() {
  document
    .getElementById("doc-search")
    .addEventListener("input", filterDoctorGrid);

  for (const option of document.querySelectorAll("[data-filter]")) {
    option.addEventListener("click", () => {
      activeFilter = option.getAttribute("data-filter");
      filterDoctorGrid();
    });
  }
}

function doctorCalendar(doctorId) {
  return document.querySelector(
    '[data-book-panel="sessions"] [data-for-doctor="' +
      doctorId +
      '"] [data-month-cal]',
  );
}

function chooseDoctor(doctorId) {
  chosenDoctor = doctorId;
  showOnly("data-for-doctor", doctorId);
  clearCalendarSelection(doctorCalendar(doctorId));
  showOnly("data-sessions", "none");
}

function setUpDoctorButtons() {
  const buttons = document.querySelectorAll("[data-see-availability]");
  for (const button of buttons) {
    button.addEventListener("click", () => {
      entryTab = "doctor";
      chooseDoctor(
        button.closest("[data-doctor-id]").getAttribute("data-doctor-id"),
      );
      showPanel("sessions");
    });
  }

  const dayDoctorButtons = document.querySelectorAll("[data-pick-doctor]");
  for (const button of dayDoctorButtons) {
    button.addEventListener("click", () => {
      const day = button.getAttribute("data-pick-day");
      entryTab = "date";
      chooseDoctor(button.getAttribute("data-pick-doctor"));
      selectCalendarDay(doctorCalendar(chosenDoctor), day);
      showOnly("data-sessions", day + "|" + chosenDoctor);
      showPanel("sessions");
    });
  }
}

function setUpCalendars() {
  const dateCalendar = document.querySelector(
    '[data-book-panel="date"] [data-month-cal]',
  );
  dateCalendar.addEventListener("calendar-day-picked", (event) => {
    showOnly("data-date-day", event.detail.day);
  });

  const doctorCalendars = document.querySelectorAll(
    '[data-book-panel="sessions"] [data-month-cal]',
  );
  for (const calendar of doctorCalendars) {
    calendar.addEventListener("calendar-day-picked", (event) => {
      showOnly("data-sessions", event.detail.day + "|" + chosenDoctor);
    });
  }
}

function setText(id, text) {
  document.getElementById(id).textContent = text;
}

function setUpSessionButtons() {
  const buttons = document.querySelectorAll("[data-book-session]");
  for (const button of buttons) {
    button.addEventListener("click", () => {
      setText("sum-datetime", button.getAttribute("data-session"));
      setText("sum-number", "#" + button.getAttribute("data-number"));
      setText("sum-eta", button.getAttribute("data-eta"));
      setText("step-datetime", button.getAttribute("data-step"));
      showPanel("details");
    });
  }
}

function setUpDetailsStep() {
  const whoOptions = document.querySelectorAll("[data-who]");
  for (const option of whoOptions) {
    option.addEventListener("click", () => {
      const who = option.getAttribute("data-who");
      for (const other of whoOptions) {
        other.classList.toggle("is-active", other === option);
      }
      showOnly("data-who-form", who);
      showOnly("data-who-label", who);
    });
  }

  const payOptions = document.querySelectorAll("[data-pay]");
  for (const option of payOptions) {
    option.addEventListener("click", () => {
      for (const other of payOptions) {
        other.classList.toggle("is-active", other === option);
      }
    });
  }
}
