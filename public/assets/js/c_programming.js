// ===== Mobile Sidebar Toggle (ADD ONLY) =====
(function () {
  const menuBtn = document.getElementById("menuBtn");
  const sidebar = document.querySelector(".sidebar");

  if (!menuBtn || !sidebar) return;

  function openSidebar() {
    sidebar.classList.add("is-open");
    document.body.classList.add("sidebar-open");
    menuBtn.setAttribute("aria-expanded", "true");
  }

  function closeSidebar() {
    sidebar.classList.remove("is-open");
    document.body.classList.remove("sidebar-open");
    menuBtn.setAttribute("aria-expanded", "false");
  }

  function toggleSidebar() {
    const isOpen = sidebar.classList.contains("is-open");
    if (isOpen) closeSidebar();
    else openSidebar();
  }

  menuBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    toggleSidebar();
  });

  // Click outside sidebar to close
  document.addEventListener("click", (e) => {
    const isOpen = sidebar.classList.contains("is-open");
    if (!isOpen) return;

    if (!sidebar.contains(e.target) && e.target !== menuBtn) {
      closeSidebar();
    }
  });

  // Close when clicking a sidebar link (nice UX)
  sidebar.addEventListener("click", (e) => {
    const a = e.target.closest("a");
    if (!a) return;
    closeSidebar();
  });

  // Close on ESC
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeSidebar();
  });
})();



// ===== FINAL FIX: Header Menu vs Lesson Sidebar (Independent) =====
(function () {
  const headerBtn = document.getElementById("menuBtn");        // ☰ Header
  const navLinks = document.getElementById("navLinks");        // Blue menu
  const lessonBtn = document.getElementById("sidebarToggle");  // ☰ Lesson
  const sidebar = document.querySelector(".sidebar");          // White sidebar

  if (!headerBtn || !navLinks || !lessonBtn || !sidebar) return;

  // ---------- HEADER BUTTON ----------
  headerBtn.addEventListener(
    "click",
    function (e) {
      e.preventDefault();
      e.stopImmediatePropagation();

      // Close lesson sidebar FIRST
      sidebar.classList.remove("is-open");
      document.body.classList.remove("sidebar-open");
      lessonBtn.setAttribute("aria-expanded", "false");

      // Toggle ONLY navbar
      navLinks.classList.toggle("open");
      headerBtn.setAttribute(
        "aria-expanded",
        navLinks.classList.contains("open") ? "true" : "false"
      );
    },
    true
  );

  // ---------- LESSON BUTTON ----------
  lessonBtn.addEventListener(
    "click",
    function (e) {
      e.preventDefault();
      e.stopImmediatePropagation();

      // Close header navbar FIRST
      navLinks.classList.remove("open");
      headerBtn.setAttribute("aria-expanded", "false");

      // Toggle ONLY lesson sidebar
      sidebar.classList.toggle("is-open");
      document.body.classList.toggle("sidebar-open");
      lessonBtn.setAttribute(
        "aria-expanded",
        sidebar.classList.contains("is-open") ? "true" : "false"
      );
    },
    true
  );

  // ---------- CLICK OUTSIDE CLOSE ----------
  document.addEventListener("click", function (e) {
    const insideSidebar = sidebar.contains(e.target);
    const insideNavbar = navLinks.contains(e.target);

    if (!insideSidebar && !insideNavbar) {
      sidebar.classList.remove("is-open");
      navLinks.classList.remove("open");
      document.body.classList.remove("sidebar-open");
      headerBtn.setAttribute("aria-expanded", "false");
      lessonBtn.setAttribute("aria-expanded", "false");
    }
  });
})();
