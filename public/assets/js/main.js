document.addEventListener("DOMContentLoaded", () => {
  const menuBtn = document.getElementById("menuBtn");
  const navLinks = document.getElementById("navLinks");

  // ✅ Reset state on load (stop auto-open)
  navLinks.classList.remove("open");
  menuBtn.textContent = "☰";
  menuBtn.setAttribute("aria-expanded", "false");

  // Toggle open/close when clicking button
  menuBtn.addEventListener("click", (e) => {
    e.stopPropagation();

    const isOpen = navLinks.classList.toggle("open");
    menuBtn.textContent = isOpen ? "✖" : "☰";
    menuBtn.setAttribute("aria-expanded", isOpen ? "true" : "false");
  });

  // Close when clicking outside
  document.addEventListener("click", (e) => {
    if (!navLinks.classList.contains("open")) return;

    if (!navLinks.contains(e.target) && !menuBtn.contains(e.target)) {
      navLinks.classList.remove("open");
      menuBtn.textContent = "☰";
      menuBtn.setAttribute("aria-expanded", "false");
    }
  });

  // Close when clicking a link
  navLinks.querySelectorAll("a").forEach(a => {
    a.addEventListener("click", () => {
      navLinks.classList.remove("open");
      menuBtn.textContent = "☰";
      menuBtn.setAttribute("aria-expanded", "false");
    });
  });
});

