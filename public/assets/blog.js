const state = {
  page: 1,
  search: "",
  category: new URLSearchParams(window.location.search).get("category") || "",
  tag: new URLSearchParams(window.location.search).get("tag") || "",
  loading: false,
  hasMore: true,
  timer: null
};

const grid = document.getElementById("blogGrid");
const loader = document.getElementById("blogLoader");
const emptyState = document.getElementById("emptyState");
const searchInput = document.getElementById("blogSearch");
const clearButton = document.getElementById("clearFilters");

function setActiveCategory(categoryId) {
  document.querySelectorAll("[data-category]").forEach((button) => {
    button.classList.toggle("active", button.dataset.category === categoryId);
  });
}

function setActiveTag(tagId) {
  document.querySelectorAll("[data-tag]").forEach((button) => {
    button.classList.toggle("active", button.dataset.tag === tagId);
  });
}

function resetAndLoad() {
  state.page = 1;
  state.hasMore = true;
  grid.innerHTML = "";
  emptyState.style.display = "none";
  loadBlogs();
}

async function loadBlogs() {
  if (state.loading || !state.hasMore) {
    return;
  }

  state.loading = true;
  loader.style.display = "block";

  const params = new URLSearchParams({
    page: state.page,
    search: state.search,
    category: state.category,
    tag: state.tag
  });

  try {
    const response = await fetch(`blog-feed.php?${params.toString()}`);
    const data = await response.json();

    if (state.page === 1 && data.count === 0) {
      emptyState.style.display = "block";
    }

    grid.insertAdjacentHTML("beforeend", data.html);
    state.hasMore = data.hasMore;
    state.page += 1;
  } catch (error) {
    emptyState.textContent = "Unable to load blogs right now.";
    emptyState.style.display = "block";
  } finally {
    state.loading = false;
    loader.style.display = "none";
  }
}

if (searchInput) {
  searchInput.addEventListener("input", () => {
    window.clearTimeout(state.timer);
    state.timer = window.setTimeout(() => {
      state.search = searchInput.value.trim();
      resetAndLoad();
    }, 280);
  });
}

document.querySelectorAll("[data-category]").forEach((button) => {
  button.addEventListener("click", () => {
    state.category = button.dataset.category;
    setActiveCategory(state.category);
    resetAndLoad();
  });
});

document.querySelectorAll("[data-tag]").forEach((button) => {
  button.addEventListener("click", () => {
    state.tag = state.tag === button.dataset.tag ? "" : button.dataset.tag;
    setActiveTag(state.tag);
    resetAndLoad();
  });
});

if (clearButton) {
  clearButton.addEventListener("click", () => {
    state.search = "";
    state.category = "";
    state.tag = "";
    if (searchInput) {
      searchInput.value = "";
    }
    setActiveCategory("");
    setActiveTag("");
    resetAndLoad();
  });
}

window.addEventListener("scroll", () => {
  const nearBottom = window.innerHeight + window.scrollY >= document.body.offsetHeight - 700;
  if (nearBottom) {
    loadBlogs();
  }
});

setActiveCategory(state.category);
setActiveTag(state.tag);
loadBlogs();
