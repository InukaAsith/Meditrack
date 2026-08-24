let dashStatusFilter = "all";
let dashDoctorFilter = "all";

function filterDashboardRows() {
  const rows = document.querySelectorAll(".data-table tbody .data-table__row");
  rows.forEach((row) => {
    const statusMatches = dashStatusFilter === "all" || row.getAttribute("data-group") === dashStatusFilter;
    const doctorMatches = dashDoctorFilter === "all" || row.getAttribute("data-doc") === dashDoctorFilter;
    row.hidden = !(statusMatches && doctorMatches);
  });
}

const statusPillGroup = document.querySelector("[data-filter-group]");
if (statusPillGroup) {
  const pills = statusPillGroup.querySelectorAll("[data-filter]");
  pills.forEach((pill) => {
    pill.addEventListener("click", () => {
      pills.forEach((other) => other.classList.toggle("is-active", other === pill));
      dashStatusFilter = pill.getAttribute("data-filter");
      filterDashboardRows();
    });
  });
}

const dashDoctorSelect = document.getElementById("dash-doc-select");
if (dashDoctorSelect) {
  dashDoctorSelect.addEventListener("change", () => {
    dashDoctorFilter = dashDoctorSelect.value;
    filterDashboardRows();
  });
}

const messageBoard = document.getElementById("msg-board");
const messageDoctorSelect = document.getElementById("msg-doc-select");
const messageEmpty = document.getElementById("msg-empty");
const messageCompose = document.getElementById("msg-compose");
const messagePostButton = document.getElementById("msg-post");

function selectedMessageDoctor() {
  if (messageDoctorSelect) return messageDoctorSelect.value;
  return "all";
}

function filterMessageBoard() {
  if (!messageBoard) return;
  const doctor = selectedMessageDoctor();
  let shownCount = 0;

  messageBoard.querySelectorAll("[data-doc]").forEach((post) => {
    const postDoctor = post.getAttribute("data-doc");
    const show = doctor === "all" || postDoctor === "all" || postDoctor === doctor;
    post.hidden = !show;
    if (show) shownCount++;
  });

  if (messageEmpty) messageEmpty.hidden = shownCount !== 0;
}

function postMessage() {
  if (!messageCompose || !messageBoard) return;
  const text = messageCompose.value.trim();
  if (!text) return;

  const template = document.getElementById("msg-post-template");
  const post = template.content.firstElementChild.cloneNode(true);
  post.setAttribute("data-doc", selectedMessageDoctor());
  post.querySelector("p").textContent = text;

  if (messageEmpty) {
    messageBoard.insertBefore(post, messageEmpty);
  } else {
    messageBoard.appendChild(post);
  }
  messageCompose.value = "";
  filterMessageBoard();
}

if (messageDoctorSelect) messageDoctorSelect.addEventListener("change", filterMessageBoard);
if (messagePostButton) messagePostButton.addEventListener("click", postMessage);
if (messageCompose) {
  messageCompose.addEventListener("keydown", (event) => {
    if (event.key === "Enter") {
      event.preventDefault();
      postMessage();
    }
  });
}

filterMessageBoard();
