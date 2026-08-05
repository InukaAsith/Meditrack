const dashTimerDisplay = document.getElementById("timer-display");
const dashTimer = document.getElementById("consult-timer");
let dashSeconds = 7 * 60 + 12;

if (dashTimerDisplay) {
  setInterval(tickDashTimer, 1000);
}

function tickDashTimer() {
  dashSeconds++;
  const minutes = Math.floor(dashSeconds / 60);
  const seconds = dashSeconds % 60;
  dashTimerDisplay.textContent =
    String(minutes).padStart(2, "0") + ":" + String(seconds).padStart(2, "0");

  if (minutes >= 15 && dashTimer) {
    dashTimer.classList.add("consultation-current-card__timer--over");
  }
}

document.querySelectorAll("[data-modal-open]").forEach((btn) => {
  btn.addEventListener("click", () => {
    const id = btn.getAttribute("data-modal-open");
    const modal = document.getElementById(id);
    if (modal && typeof modal.showModal === "function") {
      modal.showModal();
    }
  });
});

document.querySelectorAll("[data-modal-close]").forEach((btn) => {
  btn.addEventListener("click", () => {
    const id = btn.getAttribute("data-modal-close");
    const modal = document.getElementById(id);
    if (modal && typeof modal.close === "function") {
      modal.close();
    }
  });
});

