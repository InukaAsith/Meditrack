document.addEventListener("DOMContentLoaded", () => {
  setUpAlertButtons();
  setUpEmergencyModal();
});

function setUpAlertButtons() {
  document.querySelectorAll(".supporting-alert-btn").forEach((button) => {
    button.addEventListener("click", () => {
      const alertItem = button.closest(".supporting-alert-item");
      if (alertItem) alertItem.classList.add("is-done");
    });
  });
}

function setUpEmergencyModal() {
  const modal = document.getElementById("emergency-modal");
  if (!modal) return;

  function openModal() {
    modal.hidden = false;
  }
  function closeModal() {
    modal.hidden = true;
  }

  const openButton = document.getElementById("emergency-btn");
  const closeButton = document.getElementById("emergency-modal-close");
  const cancelButton = document.getElementById("emergency-cancel-btn");
  if (openButton) openButton.addEventListener("click", openModal);
  if (closeButton) closeButton.addEventListener("click", closeModal);
  if (cancelButton) cancelButton.addEventListener("click", closeModal);

  modal.addEventListener("click", (event) => {
    if (event.target === modal) closeModal();
  });
  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && !modal.hidden) closeModal();
  });

  const form = document.getElementById("emergency-form");
  if (form) {
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      closeModal();
    });
  }
}
