document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.getElementById("sidebarToggle");
  const close = document.getElementById("sidebarClose");
  const sidebar = document.querySelector(".sidebar");

  if (!toggle || !sidebar) {
    return;
  }

  const setSidebarState = (isOpen) => {
    sidebar.classList.toggle("is-open", isOpen);
    document.body.classList.toggle("sidebar-open", isOpen);
    toggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
  };

  toggle.addEventListener("click", (event) => {
    event.stopPropagation();
    setSidebarState(!sidebar.classList.contains("is-open"));
  });

  close?.addEventListener("click", () => setSidebarState(false));

  sidebar.addEventListener("click", (event) => {
    if (event.target.closest("a")) {
      setSidebarState(false);
    }
  });

  document.addEventListener("click", (event) => {
    if (sidebar.classList.contains("is-open") && !sidebar.contains(event.target) && !toggle.contains(event.target)) {
      setSidebarState(false);
    }
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      setSidebarState(false);
    }
  });

  window.addEventListener("resize", () => {
    if (window.innerWidth > 768) {
      setSidebarState(false);
    }
  });
});
