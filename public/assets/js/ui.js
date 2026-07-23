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
