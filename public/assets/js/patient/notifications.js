const markAllReadBtn = document.getElementById("mark-all-read");

if (markAllReadBtn) {
  markAllReadBtn.addEventListener("click", () => {
    const dots = document.querySelectorAll(".notif-card__unread");
    for (const dot of dots) {
      dot.remove();
    }
  });
}
