const emergencyForms = document.querySelectorAll("[data-emg-form]");

emergencyForms.forEach((form) => {
  setUpEmergencyTabs(form);

  const sendButton = form.querySelector("[data-emg-send]");
  if (sendButton) {
    sendButton.addEventListener("click", () => sendEmergencyPatient(form));
  }
});

function setUpEmergencyTabs(form) {
  const tabBar = form.querySelector("[data-emg-tabs]");
  if (!tabBar) return;

  const tabs = tabBar.querySelectorAll("[data-emg-tab]");
  const panes = form.querySelectorAll("[data-emg-pane]");
  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      const name = tab.getAttribute("data-emg-tab");
      tabs.forEach((other) => other.classList.toggle("is-active", other === tab));
      panes.forEach((pane) => {
        pane.hidden = pane.getAttribute("data-emg-pane") !== name;
      });
    });
  });
}

function sendEmergencyPatient(form) {
  let kind = "guest";
  const activeTab = form.querySelector("[data-emg-tabs] .is-active");
  if (activeTab) kind = activeTab.getAttribute("data-emg-tab");

  let name = "Emergency patient";
  if (kind === "guest") {
    const nameInput = form.querySelector('[data-emg-pane="guest"] input');
    name = "Unnamed guest";
    if (nameInput && nameInput.value.trim()) name = nameInput.value.trim();
  } else if (kind === "id") {
    const idInput = form.querySelector('[data-emg-pane="id"] input');
    if (idInput && idInput.value.trim()) name = idInput.value.trim();
  }

  let doctor = "the on-call doctor";
  const doctorSelect = form.querySelector("select");
  if (doctorSelect) doctor = doctorSelect.value;

  window.location.href = "/staff/receptionist/appointments?paused=1&name=" +
    encodeURIComponent(name) + "&with=" + encodeURIComponent(doctor);
}
