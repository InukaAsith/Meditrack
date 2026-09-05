const liveClock = document.querySelector("[data-live-clock]");

function updateClock() {
  liveClock.textContent = new Date().toLocaleTimeString("en-US", {
    hour: "2-digit",
    minute: "2-digit",
    timeZone: "Asia/Colombo",
  });
}

if (liveClock) {
  updateClock();
  setInterval(updateClock, 30000);
}

document.addEventListener("supporting:doctor", (event) => {
  const code = event.detail.code;
  document.querySelectorAll("[data-dash-panel]").forEach((panel) => {
    panel.hidden = panel.getAttribute("data-dash-panel") !== code;
  });
  window.scrollTo({ top: 0, behavior: "smooth" });
});
