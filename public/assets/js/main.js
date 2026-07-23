document.addEventListener("DOMContentLoaded", () => {
  const menuBtn = document.getElementById("menuBtn");
  const navLinks = document.getElementById("navLinks");
  const menuIcon = menuBtn?.querySelector("i");

  if (!menuBtn || !navLinks) {
    return;
  }

  const setMenuState = (isOpen) => {
    navLinks.classList.toggle("open", isOpen);
    menuBtn.setAttribute("aria-expanded", isOpen ? "true" : "false");

    if (menuIcon) {
      menuIcon.classList.toggle("bx-menu", !isOpen);
      menuIcon.classList.toggle("bx-x", isOpen);
    }
  };

  setMenuState(false);

  menuBtn.addEventListener("click", (event) => {
    event.stopPropagation();
    setMenuState(!navLinks.classList.contains("open"));
  });

  document.addEventListener("click", (event) => {
    if (!navLinks.classList.contains("open")) {
      return;
    }

    if (!navLinks.contains(event.target) && !menuBtn.contains(event.target)) {
      setMenuState(false);
    }
  });

  navLinks.querySelectorAll("a, button").forEach((item) => {
    item.addEventListener("click", () => setMenuState(false));
  });
});
