const statusBars = document.querySelectorAll("[data-statseg]");

statusBars.forEach((bar) => {
  const options = bar.querySelectorAll("[data-state]");
  options.forEach((option) => {
    option.addEventListener("click", () => {
      options.forEach((other) => {
        other.classList.toggle("is-active", other === option);
      });
    });
  });
});
