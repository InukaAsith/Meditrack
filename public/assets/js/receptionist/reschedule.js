const reschedulePage = document.querySelector("[data-reschedule]");

let rescheduleDay = "";
let rescheduleSlot = "";

if (reschedulePage) {
  const startDay = reschedulePage.querySelector(".reschedule-day.is-active");
  if (startDay) rescheduleDay = startDay.getAttribute("data-day");

  const dayButtons = reschedulePage.querySelectorAll("[data-presch-days] .reschedule-day");
  dayButtons.forEach((button) => {
    button.addEventListener("click", () => {
      dayButtons.forEach((other) => other.classList.toggle("is-active", other === button));
      rescheduleDay = button.getAttribute("data-day");
      updateRescheduleSummary();
    });
  });

  const slotButtons = reschedulePage.querySelectorAll("[data-presch-slots] .reschedule-slot");
  slotButtons.forEach((button) => {
    button.addEventListener("click", () => {
      slotButtons.forEach((other) => other.classList.toggle("is-active", other === button));
      rescheduleSlot = button.getAttribute("data-slot");
      updateRescheduleSummary();
    });
  });

  const confirmButton = reschedulePage.querySelector("[data-presch-confirm]");
  if (confirmButton) {
    confirmButton.addEventListener("click", () => {
      window.location.href = "/staff/receptionist/appointments";
    });
  }
}

function updateRescheduleSummary() {
  const summary = reschedulePage.querySelector("[data-presch-new]");
  const confirmButton = reschedulePage.querySelector("[data-presch-confirm]");

  if (summary) {
    if (rescheduleSlot) {
      summary.textContent = rescheduleDay + " at " + rescheduleSlot;
    } else {
      summary.textContent = "Pick a day & time";
    }
  }
  if (confirmButton) confirmButton.disabled = rescheduleSlot === "";
}
