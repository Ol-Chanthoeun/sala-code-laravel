const searchInput = document.getElementById("searchInput");
const searchBtn = document.getElementById("searchBtn");
const cards = document.querySelectorAll(".card");
const noResult = document.getElementById("noResult");

function filterCourses() {
  const keyword = searchInput.value.trim().toLowerCase();
  let found = false;

  cards.forEach(card => {
    const title = (card.dataset.title || "").toLowerCase();
    const h3 = card.querySelector("h3")?.innerText.toLowerCase() || "";

    if (title.includes(keyword) || h3.includes(keyword)) {
      card.style.display = "";      
      found = true;
    } else {
      card.style.display = "none";
    }
  });

  if (noResult) {
    noResult.style.display = found ? "none" : "block";
  }
}

// Filter while typing
searchInput.addEventListener("input", filterCourses);

// Filter when click button
searchBtn.addEventListener("click", filterCourses);

// Press Enter to search
searchInput.addEventListener("keydown", (e) => {
  if (e.key === "Enter") filterCourses();
});
