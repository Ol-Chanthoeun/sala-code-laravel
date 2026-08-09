document.addEventListener("DOMContentLoaded", () => {
  const menuBtn = document.getElementById("menuBtn");
  const navLinks = document.getElementById("navLinks");
  const navBackdrop = document.getElementById("navBackdrop");
  const menuIcon = menuBtn?.querySelector("i");

  if (!menuBtn || !navLinks) {
    return;
  }

  const setMenuState = (isOpen) => {
    navLinks.classList.toggle("open", isOpen);
    navBackdrop?.classList.toggle("open", isOpen);
    document.body.classList.toggle("mobile-nav-open", isOpen);
    menuBtn.setAttribute("aria-expanded", isOpen ? "true" : "false");
    menuBtn.setAttribute("aria-label", isOpen ? "Close menu" : "Open menu");
    navBackdrop?.setAttribute("aria-hidden", isOpen ? "false" : "true");

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

  navBackdrop?.addEventListener("click", () => setMenuState(false));

  document.addEventListener("click", (event) => {
    if (!navLinks.classList.contains("open")) {
      return;
    }

    if (!navLinks.contains(event.target) && !menuBtn.contains(event.target)) {
      setMenuState(false);
    }
  });

  navLinks.querySelectorAll("a, .nav-logout-form button, .account-dropdown__logout button").forEach((item) => {
    item.addEventListener("click", () => setMenuState(false));
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      setMenuState(false);
    }
  });

  window.addEventListener("resize", () => {
    if (window.innerWidth > 1100) {
      setMenuState(false);
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const popovers = Array.from(document.querySelectorAll(".navbar-popover"));

  const closePopovers = (except = null) => {
    popovers.forEach((popover) => {
      if (popover === except) return;
      popover.querySelector(".navbar-popover-trigger")?.setAttribute("aria-expanded", "false");
      const dropdown = popover.querySelector(".navbar-dropdown");
      if (dropdown) dropdown.hidden = true;
    });
  };

  popovers.forEach((popover) => {
    const trigger = popover.querySelector(".navbar-popover-trigger");
    const dropdown = popover.querySelector(".navbar-dropdown");
    if (!trigger || !dropdown) return;

    trigger.addEventListener("click", (event) => {
      event.stopPropagation();
      const willOpen = dropdown.hidden;
      closePopovers(popover);
      dropdown.hidden = !willOpen;
      trigger.setAttribute("aria-expanded", willOpen ? "true" : "false");
    });
  });

  document.addEventListener("click", (event) => {
    if (!popovers.some((popover) => popover.contains(event.target))) closePopovers();
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") closePopovers();
  });

  const search = document.querySelector(".navbar-search");
  const input = search?.querySelector("input[type='search']");
  const results = search?.querySelector(".navbar-search-results");
  const clearButton = search?.querySelector(".navbar-search__clear");
  let searchTimer;
  let requestController;

  if (!search || !input || !results || !clearButton) return;

  const closeResults = () => {
    results.hidden = true;
    input.setAttribute("aria-expanded", "false");
  };

  const showMessage = (message) => {
    results.replaceChildren();
    const state = document.createElement("div");
    state.className = "navbar-search-state";
    state.textContent = message;
    results.append(state);
    results.hidden = false;
    input.setAttribute("aria-expanded", "true");
  };

  const renderResults = (groups) => {
    results.replaceChildren();
    let count = 0;

    groups.forEach((group) => {
      if (!Array.isArray(group.items) || group.items.length === 0) return;
      const section = document.createElement("section");
      section.className = "navbar-search-group";
      const heading = document.createElement("strong");
      heading.textContent = group.label;
      section.append(heading);

      group.items.forEach((item) => {
        const link = document.createElement("a");
        link.href = item.url;
        link.setAttribute("role", "option");
        const title = document.createElement("span");
        title.textContent = item.title;
        const subtitle = document.createElement("small");
        subtitle.textContent = item.subtitle;
        link.append(title, subtitle);
        section.append(link);
        count += 1;
      });
      results.append(section);
    });

    if (count === 0) return showMessage("No results found");
    results.hidden = false;
    input.setAttribute("aria-expanded", "true");
  };

  input.addEventListener("input", () => {
    window.clearTimeout(searchTimer);
    requestController?.abort();
    const query = input.value.trim();
    clearButton.hidden = query.length === 0;

    if (query.length < 2) {
      closeResults();
      return;
    }

    showMessage("Searching...");
    searchTimer = window.setTimeout(async () => {
      requestController = new AbortController();
      try {
        const response = await fetch(`${search.dataset.searchUrl}?q=${encodeURIComponent(query)}`, {
          headers: { Accept: "application/json" },
          signal: requestController.signal,
        });
        if (!response.ok) throw new Error("Search failed");
        const data = await response.json();
        if (input.value.trim() === query) renderResults(data.groups || []);
      } catch (error) {
        if (error.name !== "AbortError") showMessage("Search is temporarily unavailable");
      }
    }, 250);
  });

  input.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      closeResults();
      input.blur();
    } else if (event.key === "ArrowDown" && !results.hidden) {
      event.preventDefault();
      results.querySelector("a")?.focus();
    }
  });

  clearButton.addEventListener("click", () => {
    input.value = "";
    clearButton.hidden = true;
    closeResults();
    input.focus();
  });

  document.addEventListener("click", (event) => {
    if (!search.contains(event.target)) closeResults();
  });
});
