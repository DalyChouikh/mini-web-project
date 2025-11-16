export class HeaderView {
  constructor(searchInput, themeToggle) {
    this.searchInput = searchInput;
    this.themeToggle = themeToggle;
    this.themeIcon = themeToggle?.querySelector(".theme-icon");
  }

  bindSearch(handler) {
    if (!this.searchInput) return;
    this.searchInput.addEventListener("input", (e) => {
      handler(e.target.value);
    });
  }

  bindThemeToggle(handler) {
    if (!this.themeToggle) return;
    this.themeToggle.addEventListener("click", handler);
  }

  updateThemeIcon(theme) {
    if (!this.themeIcon) return;
    this.themeIcon.textContent = theme === "dark" ? "🌙" : "☀️";
  }
}
