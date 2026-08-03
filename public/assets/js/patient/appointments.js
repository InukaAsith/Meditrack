const apptsList = document.getElementById("appts-list");

if (apptsList) {
  const openRows = document.querySelectorAll("[data-appt-open]");
  for (const row of openRows) {
    row.addEventListener("click", (e) => {
      if (e.target.closest("[data-appt-action], a, button")) return;
      showAppointmentDetail(row.getAttribute("data-appt-open"));
    });
  }

  const openDetailButtons = document.querySelectorAll(
    "[data-appt-open-detail]",
  );
  for (const btn of openDetailButtons) {
    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      showAppointmentDetail(btn.getAttribute("data-appt-open-detail"));
    });
  }

  const backButtons = document.querySelectorAll("[data-appt-back]");
  for (const btn of backButtons) {
    btn.addEventListener("click", () => showAppointmentDetail(null));
  }

  const rescheduleButtons = document.querySelectorAll("[data-appt-reschedule]");
  for (const btn of rescheduleButtons) {
    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      showAppointmentDetail("reschedule");
    });
  }

  setUpReschedulePanel();
}

function showAppointmentDetail(code) {
  const details = document.querySelectorAll("[data-appt-detail]");

  apptsList.hidden = code !== null;
  for (const detail of details) {
    detail.hidden = detail.getAttribute("data-appt-detail") !== code;
  }
  window.scrollTo({ top: 0, behavior: "smooth" });
}

function setUpReschedulePanel() {
  const panel = document.querySelector('[data-appt-detail="reschedule"]');
  if (!panel) return;

  const dayButtons = panel.querySelectorAll(
    "[data-presch-days] .reschedule-day",
  );
  const slotButtons = panel.querySelectorAll(
    "[data-presch-slots] .reschedule-slot",
  );
  const newLabel = panel.querySelector("[data-presch-new]");
  const confirmBtn = panel.querySelector("[data-presch-confirm]");

  let chosenDay = "Mon 27 Jul";
  let chosenTime = "";

  function refresh() {
    if (newLabel)
      newLabel.textContent = chosenTime
        ? chosenDay + " at " + chosenTime
        : "Pick a day & time";
    if (confirmBtn) confirmBtn.disabled = !chosenTime;
  }

  for (const dayBtn of dayButtons) {
    dayBtn.addEventListener("click", () => {
      for (const other of dayButtons) {
        other.classList.toggle("is-active", other === dayBtn);
      }
      chosenDay = dayBtn.getAttribute("data-day");
      refresh();
    });
  }

  for (const slotBtn of slotButtons) {
    slotBtn.addEventListener("click", () => {
      for (const other of slotButtons) {
        other.classList.toggle("is-active", other === slotBtn);
      }
      chosenTime = slotBtn.getAttribute("data-slot");
      refresh();
    });
  }

  if (confirmBtn) {
    confirmBtn.addEventListener("click", () => {
      showAppointmentDetail(null);
    });
  }
}
