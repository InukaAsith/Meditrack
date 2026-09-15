document.querySelectorAll("[data-doc-selector]").forEach((selector) => {
  const pills = selector.querySelectorAll("[data-doc-pill]");

  pills.forEach((pill) => {
    pill.addEventListener("click", () => {
      pills.forEach((other) => other.classList.toggle("is-active", other === pill));

      const doctor = {
        code: pill.getAttribute("data-doc-pill"),
        name: pill.getAttribute("data-doc-name"),
        specialty: pill.getAttribute("data-doc-specialty"),
      };
      document.dispatchEvent(new CustomEvent("supporting:doctor", { detail: doctor }));
    });
  });

  const searchBox = selector.querySelector("[data-doc-search]");
  if (searchBox) {
    searchBox.addEventListener("input", () => {
      const query = searchBox.value.trim().toLowerCase();
      pills.forEach((pill) => {
        const name = (pill.getAttribute("data-doc-name") || "").toLowerCase();
        pill.closest("[data-doc-pill-wrap]").hidden = query !== "" && !name.includes(query);
      });
    });
  }
});
