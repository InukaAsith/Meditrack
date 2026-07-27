document.addEventListener("DOMContentLoaded", () => {
  const dayCells = document.querySelectorAll(".month-cal__cell[data-day]");

  for (const cell of dayCells) {
    cell.addEventListener("click", () => {
      for (const other of dayCells) {
        other.classList.remove("is-selected");
      }
      cell.classList.add("is-selected");

      showDay(cell.getAttribute("data-day"));
    });
  }
});

function showDay(day) {
  const blocks = document.querySelectorAll("[data-day-appts]");
  for (const block of blocks) {
    block.hidden = block.getAttribute("data-day-appts") !== day;
  }
}
