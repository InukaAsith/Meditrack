document.addEventListener("DOMContentLoaded", () => {
  const markAllButton = document.getElementById("notifications-mark-all");
  const unreadItems = document.querySelectorAll(".notif-centre__item--unread");

  unreadItems.forEach((item) => {
    item.addEventListener("click", () => {
      item.classList.remove("notif-centre__item--unread");
    });
  });

  markAllButton.addEventListener("click", () => {
    unreadItems.forEach((item) => {
      item.classList.remove("notif-centre__item--unread");
    });
    markAllButton.disabled = true;
  });
});
