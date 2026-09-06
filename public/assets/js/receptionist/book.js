document.addEventListener("DOMContentLoaded", () => {
  setUpBackButtons();
  setUpDoctorSearch();
  setUpDoctorButtons();
  setUpCalendarAndSlots();
  setUpDetailsStep();
});

function showOnly(attribute, value) {
  const elements = document.querySelectorAll("[" + attribute + "]");
  for (const element of elements) {
    element.hidden = element.getAttribute(attribute) !== value;
  }
}

function showBookingPanel(name) {
  showOnly("data-book-panel", name);
  window.scrollTo({ top: 0, behavior: "smooth" });
}

function setUpBackButtons() {
  const buttons = document.querySelectorAll("[data-book-back]");
  for (const button of buttons) {
    button.addEventListener("click", () =>
      showBookingPanel(button.getAttribute("data-book-back")),
    );
  }
}

let bookingSpecialty = "All";

function filterBookingDoctors() {
  const query = document
    .getElementById("doc-search")
    .value.toLowerCase()
    .trim();

  const cards = document.querySelectorAll("#doc-grid [data-search]");
  for (const card of cards) {
    const matchesQuery =
      query === "" || card.getAttribute("data-search").includes(query);
    const matchesSpecialty =
      bookingSpecialty === "All" ||
      card.getAttribute("data-specialty") === bookingSpecialty;
    card.hidden = !(matchesQuery && matchesSpecialty);
  }
}

function setUpDoctorSearch() {
  document
    .getElementById("doc-search")
    .addEventListener("input", filterBookingDoctors);

  const pills = document.querySelectorAll("[data-filter]");
  for (const pill of pills) {
    pill.addEventListener("click", () => {
      for (const other of pills) {
        other.classList.toggle("is-active", other === pill);
      }
      bookingSpecialty = pill.getAttribute("data-filter");
      filterBookingDoctors();
    });
  }
}

function setUpDoctorButtons() {
  const buttons = document.querySelectorAll("[data-see-availability]");
  for (const button of buttons) {
    button.addEventListener("click", () => {
      showOnly(
        "data-for-doctor",
        button.closest("[data-doctor-id]").getAttribute("data-doctor-id"),
      );
      showBookingPanel("date");
    });
  }
}

function setUpCalendarAndSlots() {
  const slotDay = document.getElementById("slot-day");

  const calendar = document.querySelector("[data-month-cal]");
  calendar.addEventListener("calendar-day-picked", (event) => {
    const date = new Date(event.detail.day + "T00:00:00");
    slotDay.textContent = date.toLocaleDateString("en-GB", {
      weekday: "short",
      day: "2-digit",
      month: "short",
    });
    document.getElementById("session-list").hidden = false;
  });

  const slots = document.querySelectorAll("#session-list [data-slot]");
  for (const slot of slots) {
    slot.addEventListener("click", () => {
      for (const other of slots) {
        other.classList.toggle("is-active", other === slot);
      }
      document.getElementById("sum-datetime").textContent =
        slotDay.textContent + " at " + slot.getAttribute("data-slot");
      showBookingPanel("details");
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

  document.getElementById("book-confirm").addEventListener("click", () => {
    window.location.href = "/staff/receptionist/appointments";
  });
}
