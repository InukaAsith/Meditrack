const payMethodGroup = document.querySelector("[data-pay-methods]");

if (payMethodGroup) {
  const payButtons = payMethodGroup.querySelectorAll("[data-pm]");
  payButtons.forEach((button) => {
    button.addEventListener("click", () => {
      if (button.disabled) return;
      payButtons.forEach((other) => other.classList.toggle("is-active", other === button));
    });
  });
}

const walkinDoctor = document.querySelector("[data-walkin-doc]");
const walkinFee = document.querySelector("[data-walkin-fee]");

if (walkinDoctor && walkinFee) {
  walkinDoctor.addEventListener("change", () => {
    const option = walkinDoctor.options[walkinDoctor.selectedIndex];
    if (!option) return;

    const fee = parseFloat(option.getAttribute("data-fee"));
    if (isNaN(fee)) return;
    walkinFee.textContent = "Rs. " + fee.toLocaleString("en-LK", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  });
}

const lateButton = document.querySelector("[data-checkin-late]");
const latePopup = document.getElementById("checkin-late-pop");

if (lateButton && latePopup) {
  const positionInput = latePopup.querySelector("[data-late-pos]");

  lateButton.addEventListener("click", () => {
    const title = latePopup.querySelector("[data-late-title]");
    const name = lateButton.getAttribute("data-patient") || "This patient";
    if (title) title.textContent = "Late arrival: " + name;
    if (positionInput) positionInput.value = "3";
    showLatePopup();
  });

  latePopup.querySelector("[data-late-cancel]").addEventListener("click", () => {
    latePopup.hidden = true;
  });

  latePopup.querySelector("[data-late-confirm]").addEventListener("click", () => {
    latePopup.hidden = true;
  });

  document.addEventListener("click", (event) => {
    if (latePopup.hidden) return;
    if (latePopup.contains(event.target)) return;
    if (event.target.closest("[data-checkin-late]")) return;
    latePopup.hidden = true;
  });
}

function showLatePopup() {
  const buttonBox = lateButton.getBoundingClientRect();
  latePopup.hidden = false;

  const box = latePopup.querySelector(".reinsert-pop");
  const rightmostLeft = window.scrollX + document.documentElement.clientWidth - box.offsetWidth - 12;
  let left = window.scrollX + buttonBox.left;
  if (left > rightmostLeft) left = rightmostLeft;
  if (left < window.scrollX + 8) left = window.scrollX + 8;

  box.style.top = (window.scrollY + buttonBox.bottom + 6) + "px";
  box.style.left = left + "px";
}

const checkinMode = new URLSearchParams(location.search).get("mode");

if (checkinMode) {
  const modeTab = document.querySelector('[data-tab-group] [data-tab="' + checkinMode + '"]');
  if (modeTab) modeTab.click();
}
