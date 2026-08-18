const labFileButtons = document.querySelectorAll("[data-lab-file]");

for (const button of labFileButtons) {
  button.addEventListener("click", () => showLabFile(button));
}

function showLabFile(button) {
  for (const other of labFileButtons) {
    other.classList.remove("is-active");
  }
  button.classList.add("is-active");

  const fileName = button.getAttribute("data-lab-file");
  setLabText("[data-lab-name]", fileName);
  setLabText("[data-lab-docname]", fileName);
  setLabText("[data-lab-metaline]", button.getAttribute("data-lab-meta") || "");
  setLabText("[data-lab-sizeline]", button.getAttribute("data-lab-size") || "");
  setLabText(
    "[data-lab-pagesline]",
    button.getAttribute("data-lab-pages") || "1",
  );
}

function setLabText(selector, text) {
  const element = document.querySelector(selector);
  if (element) element.textContent = text;
}
