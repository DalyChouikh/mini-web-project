export class HeaderView {
  constructor(searchInput, themeToggleButton) {
    this.searchInput = searchInput;
    this.themeToggleButton = themeToggleButton;
  }

  bindSearch(handler) {
    if (!this.searchInput) {
      return;
    }
    this.searchInput.addEventListener("input", (event) => {
      handler(event.target.value || "");
    });
  }

  bindThemeToggle(handler) {
    if (!this.themeToggleButton) {
      return;
    }
    this.themeToggleButton.addEventListener("click", () => {
      handler();
    });
  }

  updateThemeIcon(theme) {
    if (!this.themeToggleButton) {
      return;
    }
    this.themeToggleButton.textContent = theme === "dark" ? "☾" : "☀";
  }
}
