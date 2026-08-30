document.querySelectorAll("[data-auto-submit]").forEach((fileInput) => {
  fileInput.addEventListener("change", () => {
    if (fileInput.files.length > 0) fileInput.form.submit();
  });
});
