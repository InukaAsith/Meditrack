const RAIL_KEY = "meditrack.rail-open";

document.addEventListener("click", handlePageClick);

function updateClock() {
  const clock = document.querySelector("[data-clock]");
  if (!clock) return;
  clock.textContent = new Date().toLocaleTimeString("en-GB", {
    hour: "2-digit",
    minute: "2-digit",
    timeZone: "Asia/Colombo",
  });
}

setInterval(updateClock, 30000);

try {
  if (localStorage.getItem(RAIL_KEY) === "1") setDrawer(true, false);
} catch (err) {
}

function handlePageClick(e) {
  if (e.target.closest("[data-drawer-toggle]")) {
    const drawer = document.querySelector("[data-drawer]");
    const isOpen = drawer && drawer.classList.contains("is-open");
    setDrawer(!isOpen, true);
    return;
  }

  handleAvatarMenus(e);

  const toggle = e.target.closest("[data-toggle]");
  if (toggle) {
    const on = toggle.classList.toggle("is-on");
    toggle.setAttribute("aria-pressed", String(on));
  }

  const tab = e.target.closest("[data-tab]");
  if (tab) {
    switchTab(tab);
  }
}

function setDrawer(open, remember) {
  const drawer = document.querySelector("[data-drawer]");
  if (!drawer) return;

  drawer.classList.toggle("is-open", open);
  const trigger = drawer.querySelector("[data-drawer-toggle]");
  if (trigger) trigger.setAttribute("aria-expanded", String(open));

  if (remember) {
    try {
      localStorage.setItem(RAIL_KEY, open ? "1" : "0");
    } catch (err) {
    }
  }
}

function handleAvatarMenus(e) {
  const avatarToggle = e.target.closest("[data-avatar-toggle]");
  const pops = document.querySelectorAll("[data-avatar-pop]");

  for (const pop of pops) {
    const wrap = pop.closest("[data-avatar-menu]");
    const clickedThisToggle =
      avatarToggle && wrap && wrap.contains(avatarToggle);

    if (clickedThisToggle) {
      pop.hidden = !pop.hidden;
      avatarToggle.setAttribute("aria-expanded", String(!pop.hidden));
      continue;
    }

    if (!pop.contains(e.target)) {
      pop.hidden = true;
      const toggle = wrap && wrap.querySelector("[data-avatar-toggle]");
      if (toggle) toggle.setAttribute("aria-expanded", "false");
    }
  }
}

function switchTab(tab) {
  const group = tab.closest("[data-tab-group]");
  if (!group) return;

  const key = tab.getAttribute("data-tab");

  const tabs = group.querySelectorAll("[data-tab]");
  for (const t of tabs) {
    t.classList.toggle("is-active", t === tab);
  }

  const panels = group.querySelectorAll("[data-tab-panel]");
  for (const panel of panels) {
    panel.hidden = panel.getAttribute("data-tab-panel") !== key;
  }
}


function ensureSystemModals() {
  if (document.getElementById("meditrack-confirm-modal")) return;

  const wrapper = document.createElement("div");
  wrapper.id = "meditrack-system-dialogs";
  wrapper.innerHTML = `
    <div class="modal-backdrop" id="meditrack-confirm-modal" hidden style="z-index: 10000;">
      <div class="modal card" role="dialog" aria-modal="true" style="max-width: 440px; border-radius: var(--r-md, 12px); border: 1px solid var(--border); box-shadow: var(--shadow-lg);">
        <div class="modal__head" style="padding: var(--sp-6, 1rem) var(--sp-7, 1.25rem) var(--sp-2, 0.5rem); display: flex; align-items: center; justify-content: space-between;">
          <h2 class="modal__title" id="meditrack-confirm-title" style="font-size: var(--fs-lg, 1.125rem); font-weight: 700; margin: 0;">Confirm Action</h2>
          <button class="modal__close" type="button" id="meditrack-confirm-close" aria-label="Close" style="background: none; border: none; font-size: 1.1rem; cursor: pointer; color: var(--text-muted); padding: 4px 8px; border-radius: 4px;">✕</button>
        </div>
        <div class="modal__body" style="padding: var(--sp-4, 0.75rem) var(--sp-7, 1.25rem) var(--sp-6, 1rem); font-size: var(--fs-sm, 0.875rem); line-height: 1.5; color: var(--text);">
          <div id="meditrack-confirm-message"></div>
        </div>
        <div class="modal__actions" style="padding: 0 var(--sp-7, 1.25rem) var(--sp-6, 1rem); display: flex; justify-content: flex-end; gap: var(--sp-3, 0.5rem);">
          <button class="btn btn--secondary" type="button" id="meditrack-confirm-cancel">Cancel</button>
          <button class="btn btn--danger" type="button" id="meditrack-confirm-ok">Confirm</button>
        </div>
      </div>
    </div>

    <div class="modal-backdrop" id="meditrack-alert-modal" hidden style="z-index: 10000;">
      <div class="modal card" role="dialog" aria-modal="true" style="max-width: 440px; border-radius: var(--r-md, 12px); border: 1px solid var(--border); box-shadow: var(--shadow-lg);">
        <div class="modal__head" style="padding: var(--sp-6, 1rem) var(--sp-7, 1.25rem) var(--sp-2, 0.5rem); display: flex; align-items: center; justify-content: space-between;">
          <h2 class="modal__title" id="meditrack-alert-title" style="font-size: var(--fs-lg, 1.125rem); font-weight: 700; margin: 0;">Notice</h2>
          <button class="modal__close" type="button" id="meditrack-alert-close" aria-label="Close" style="background: none; border: none; font-size: 1.1rem; cursor: pointer; color: var(--text-muted); padding: 4px 8px; border-radius: 4px;">✕</button>
        </div>
        <div class="modal__body" style="padding: var(--sp-4, 0.75rem) var(--sp-7, 1.25rem) var(--sp-6, 1rem); font-size: var(--fs-sm, 0.875rem); line-height: 1.5; color: var(--text);">
          <div id="meditrack-alert-message"></div>
        </div>
        <div class="modal__actions" style="padding: 0 var(--sp-7, 1.25rem) var(--sp-6, 1rem); display: flex; justify-content: flex-end; gap: var(--sp-3, 0.5rem);">
          <button class="btn btn--primary" type="button" id="meditrack-alert-ok">OK</button>
        </div>
      </div>
    </div>
  `;
  document.body.appendChild(wrapper);
}

window.showConfirmDialog = function({
  title = "Confirm Action",
  message = "Are you sure you want to proceed?",
  confirmText = "Confirm",
  cancelText = "Cancel",
  danger = true,
  onConfirm = null,
  onCancel = null,
} = {}) {
  return new Promise((resolve) => {
    ensureSystemModals();
    const modal = document.getElementById("meditrack-confirm-modal");
    const titleEl = document.getElementById("meditrack-confirm-title");
    const msgEl = document.getElementById("meditrack-confirm-message");
    const okBtn = document.getElementById("meditrack-confirm-ok");
    const cancelBtn = document.getElementById("meditrack-confirm-cancel");
    const closeBtn = document.getElementById("meditrack-confirm-close");

    titleEl.textContent = title;
    msgEl.innerHTML = message;
    okBtn.textContent = confirmText;
    okBtn.className = danger ? "btn btn--danger" : "btn btn--primary";
    cancelBtn.textContent = cancelText;

    function cleanup() {
      modal.hidden = true;
      document.removeEventListener("keydown", onKeyDown);
      okBtn.onclick = null;
      cancelBtn.onclick = null;
      closeBtn.onclick = null;
      modal.onclick = null;
    }

    function onKeyDown(e) {
      if (e.key === "Escape") {
        cleanup();
        if (onCancel) onCancel();
        resolve(false);
      }
    }

    okBtn.onclick = () => {
      cleanup();
      if (onConfirm) onConfirm();
      resolve(true);
    };

    cancelBtn.onclick = closeBtn.onclick = () => {
      cleanup();
      if (onCancel) onCancel();
      resolve(false);
    };

    modal.onclick = (e) => {
      if (e.target === modal) {
        cleanup();
        if (onCancel) onCancel();
        resolve(false);
      }
    };

    document.addEventListener("keydown", onKeyDown);
    modal.hidden = false;
    okBtn.focus();
  });
};

window.showAlertDialog = function(options = {}) {
  const title = (typeof options === "string" ? "Notice" : options.title) || "Notice";
  const message = (typeof options === "string" ? options : options.message) || "";
  const okText = (typeof options === "object" && options.okText) || "OK";
  const onOk = typeof options === "object" ? options.onOk : null;

  return new Promise((resolve) => {
    ensureSystemModals();
    const modal = document.getElementById("meditrack-alert-modal");
    const titleEl = document.getElementById("meditrack-alert-title");
    const msgEl = document.getElementById("meditrack-alert-message");
    const okBtn = document.getElementById("meditrack-alert-ok");
    const closeBtn = document.getElementById("meditrack-alert-close");

    titleEl.textContent = title;
    msgEl.innerHTML = message;
    okBtn.textContent = okText;

    function cleanup() {
      modal.hidden = true;
      document.removeEventListener("keydown", onKeyDown);
      okBtn.onclick = null;
      closeBtn.onclick = null;
      modal.onclick = null;
    }

    function onKeyDown(e) {
      if (e.key === "Escape") {
        cleanup();
        if (onOk) onOk();
        resolve();
      }
    }

    okBtn.onclick = closeBtn.onclick = () => {
      cleanup();
      if (onOk) onOk();
      resolve();
    };

    modal.onclick = (e) => {
      if (e.target === modal) {
        cleanup();
        if (onOk) onOk();
        resolve();
      }
    };

    document.addEventListener("keydown", onKeyDown);
    modal.hidden = false;
    okBtn.focus();
  });
};

window.alert = function(msg) {
  window.showAlertDialog({ title: "Notice", message: String(msg) });
};

document.addEventListener("submit", function(e) {
  const form = e.target;
  const confirmMsg = form.getAttribute("data-confirm");
  if (!confirmMsg) return;

  if (form.__systemConfirmed) {
    delete form.__systemConfirmed;
    return;
  }

  e.preventDefault();
  const title = form.getAttribute("data-confirm-title") || "Confirm Action";
  const okText = form.getAttribute("data-confirm-ok") || "Confirm";
  const cancelText = form.getAttribute("data-confirm-cancel") || "Cancel";
  const isDanger = form.getAttribute("data-confirm-danger") !== "false";

  window.showConfirmDialog({
    title: title,
    message: confirmMsg,
    confirmText: okText,
    cancelText: cancelText,
    danger: isDanger,
    onConfirm: () => {
      form.__systemConfirmed = true;
      form.requestSubmit ? form.requestSubmit() : form.submit();
    }
  });
});

document.addEventListener("click", function(e) {
  const trigger = e.target.closest("[data-modal-confirm]");
  if (!trigger) return;

  e.preventDefault();
  const formId = trigger.getAttribute("data-modal-confirm");
  const form = document.getElementById(formId);
  if (!form) return;

  const confirmMsg = trigger.getAttribute("data-confirm") || form.getAttribute("data-confirm");
  if (!confirmMsg) {
    form.requestSubmit ? form.requestSubmit() : form.submit();
    return;
  }

  const title = trigger.getAttribute("data-confirm-title") || form.getAttribute("data-confirm-title") || "Confirm Action";
  const okText = trigger.getAttribute("data-confirm-ok") || form.getAttribute("data-confirm-ok") || "Confirm";
  const cancelText = trigger.getAttribute("data-confirm-cancel") || form.getAttribute("data-confirm-cancel") || "Cancel";
  const isDanger = (trigger.getAttribute("data-confirm-danger") || form.getAttribute("data-confirm-danger")) !== "false";

  window.showConfirmDialog({
    title: title,
    message: confirmMsg,
    confirmText: okText,
    cancelText: cancelText,
    danger: isDanger,
    onConfirm: () => {
      form.__systemConfirmed = true;
      form.requestSubmit ? form.requestSubmit() : form.submit();
    }
  });
});
