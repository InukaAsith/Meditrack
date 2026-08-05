let staffRoleFilter = "All";
let staffSearchText = "";

function filterStaffRows() {
  let shownCount = 0;
  document
    .querySelectorAll("[data-staff-body] [data-staff-row]")
    .forEach((row) => {
      const roleMatches =
        staffRoleFilter === "All" ||
        row.getAttribute("data-role") === staffRoleFilter;
      const textMatches =
        staffSearchText === "" ||
        row.getAttribute("data-search").includes(staffSearchText);
      row.hidden = !(roleMatches && textMatches);
      if (!row.hidden) shownCount++;
    });

  const emptyMessage = document.querySelector("[data-staff-empty]");
  if (emptyMessage) emptyMessage.hidden = shownCount !== 0;
}

if (document.querySelector("[data-staff-body]")) {
  const staffSearch = document.querySelector("[data-staff-search]");
  if (staffSearch) {
    staffSearch.addEventListener("input", () => {
      staffSearchText = staffSearch.value.trim().toLowerCase();
      filterStaffRows();
    });
  }

  const roleButtons = document.querySelector("[data-staff-rolefilter]");
  if (roleButtons) {
    roleButtons.addEventListener("click", (event) => {
      const button = event.target.closest(".seg__opt");
      if (!button) return;
      roleButtons.querySelectorAll(".seg__opt").forEach((other) => {
        other.classList.toggle("is-active", other === button);
      });
      staffRoleFilter = button.getAttribute("data-role");
      filterStaffRows();
    });
  }
}

const roleSelect = document.querySelector("[data-role-select]");
if (roleSelect) {
  roleSelect.addEventListener("change", () => {
    document.querySelector("[data-doctor-fields]").hidden = roleSelect.value !== "Doctor";
  });
}

const photoInput = document.querySelector("[data-photo-input]");
if (photoInput) {
  photoInput.addEventListener("change", () => {
    if (photoInput.files.length === 0) return;
    const file = photoInput.files[0];
    document.querySelector("[data-photo-name]").textContent = file.name;

    document.querySelector("[data-photo-image]").src = URL.createObjectURL(file);
    document.querySelector("[data-photo-placeholder]").hidden = true;
    document.querySelector("[data-photo-picked]").hidden = false;
  });
}

document.querySelectorAll("[data-auto-submit]").forEach((fileInput) => {
  fileInput.addEventListener("change", () => {
    if (fileInput.files.length > 0) fileInput.form.submit();
  });
});

const templateGrid = document.querySelector("[data-tpl-grid]");
const channelButtons = document.querySelector("[data-tpl-filter]");

if (templateGrid && channelButtons) {
  channelButtons.addEventListener("click", (event) => {
    const button = event.target.closest(".seg__opt");
    if (!button) return;
    channelButtons.querySelectorAll(".seg__opt").forEach((other) => {
      other.classList.toggle("is-active", other === button);
    });

    const channel = button.getAttribute("data-chan");
    templateGrid.querySelectorAll("[data-tpl-card]").forEach((card) => {
      card.hidden =
        channel !== "all" && card.getAttribute("data-chan") !== channel;
    });
  });
}

const templateModal = document.querySelector("[data-tpl-modal]");

function updateTemplatePreview() {
  const textarea = templateModal.querySelector("[data-tpl-body]");
  const preview = templateModal.querySelector("[data-tpl-live]");
  if (!textarea || !preview) return;

  const pieces = textarea.value.split(/(\{\{\s*[a-z_]+\s*\}\})/i);

  preview.textContent = "";
  pieces.forEach((piece, index) => {
    if (index % 2 === 1) {
      const variable = document.createElement("span");
      variable.className = "var";
      variable.textContent = piece.replace(/\s/g, "");
      preview.appendChild(variable);
    } else {
      preview.appendChild(document.createTextNode(piece));
    }
  });
}

if (templateModal) {
  document.addEventListener("click", (event) => {
    const editButton = event.target.closest("[data-tpl-edit]");
    if (editButton) {
      event.preventDefault();
      const title = templateModal.querySelector("[data-tpl-modal-title]");
      const textarea = templateModal.querySelector("[data-tpl-body]");
      if (title)
        title.textContent =
          "Edit - " + (editButton.getAttribute("data-name") || "template");
      if (textarea) textarea.value = editButton.getAttribute("data-body") || "";
      updateTemplatePreview();
      templateModal.hidden = false;
      return;
    }

    if (
      event.target.closest("[data-tpl-close]") ||
      event.target === templateModal
    ) {
      templateModal.hidden = true;
    }
  });

  const textarea = templateModal.querySelector("[data-tpl-body]");
  if (textarea) textarea.addEventListener("input", updateTemplatePreview);

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && !templateModal.hidden)
      templateModal.hidden = true;
  });
}

const auditSearch = document.querySelector("[data-audit-search]");
const auditDate = document.querySelector("[data-audit-date]");
const auditUser = document.querySelector("[data-audit-user]");
const auditRole = document.querySelector("[data-audit-role]");
const auditRecord = document.querySelector("[data-audit-record]");

if (auditSearch || auditDate || auditUser || auditRole || auditRecord) {
  function filterAuditRows() {
    const q = auditSearch ? auditSearch.value.trim().toLowerCase() : "";
    const dateVal = auditDate ? auditDate.value : "all";
    const userVal = auditUser ? auditUser.value.trim().toLowerCase() : "all";
    const roleVal = auditRole ? auditRole.value.trim().toLowerCase() : "all";
    const recordVal = auditRecord ? auditRecord.value.trim().toLowerCase() : "all";

    const now = new Date();
    const todayStr = now.toISOString().slice(0, 10);

    document.querySelectorAll("[data-tab-panel]").forEach((panel) => {
      let shown = 0;
      const rows = panel.querySelectorAll("tbody tr[data-audit-row]");

      rows.forEach((row) => {
        const searchVal = row.getAttribute("data-search") || "";
        const rowUser = (row.getAttribute("data-user") || "").toLowerCase();
        const rowRole = (row.getAttribute("data-role") || "").toLowerCase();
        const rowRecord = (row.getAttribute("data-record") || "").toLowerCase();
        const rowDate = row.getAttribute("data-date") || "";
        const timeCell = row.querySelector(".audit-time");
        const timeText = timeCell ? timeCell.textContent : "";

        const matchesSearch = q === "" || searchVal.includes(q);

        const matchesUser = userVal === "all" || rowUser === userVal || rowUser.includes(userVal);

        const matchesRole = roleVal === "all" || rowRole === roleVal;

        const matchesRecord = recordVal === "all" || !row.hasAttribute("data-record") || rowRecord === recordVal;

        let matchesDate = true;
        if (dateVal === "today") {
          matchesDate = rowDate === todayStr || timeText.includes("Today");
        } else if (dateVal === "7days") {
          if (rowDate) {
            const diffDays = (now - new Date(rowDate)) / (1000 * 60 * 60 * 24);
            matchesDate = diffDays <= 7;
          }
        } else if (dateVal === "30days") {
          if (rowDate) {
            const diffDays = (now - new Date(rowDate)) / (1000 * 60 * 60 * 24);
            matchesDate = diffDays <= 30;
          }
        } else if (dateVal === "month") {
          if (rowDate) {
            matchesDate = rowDate.slice(0, 7) === todayStr.slice(0, 7);
          }
        }

        const match = matchesSearch && matchesUser && matchesRole && matchesRecord && matchesDate;
        row.hidden = !match;
        if (match) shown++;
      });

      const emptyRow = panel.querySelector("[data-audit-empty]");
      if (emptyRow) {
        emptyRow.hidden = shown > 0;
      }

      const tabKey = panel.getAttribute("data-tab-panel");
      const tabBadge = document.querySelector(`[data-count-${tabKey}]`);
      if (tabBadge) {
        tabBadge.textContent = String(shown);
      }
    });
  }

  if (auditSearch) auditSearch.addEventListener("input", filterAuditRows);
  if (auditDate) auditDate.addEventListener("change", filterAuditRows);
  if (auditUser) auditUser.addEventListener("change", filterAuditRows);
  if (auditRole) auditRole.addEventListener("change", filterAuditRows);
  if (auditRecord) auditRecord.addEventListener("change", filterAuditRows);
}

