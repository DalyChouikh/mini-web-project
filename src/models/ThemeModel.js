const STORAGE_KEY = "blog-theme";

export class ThemeModel {
  getTheme() {
    try {
      const stored = localStorage.getItem(STORAGE_KEY);
      if (stored === "light" || stored === "dark") {
        return stored;
      }
    } catch (error) {}

    if (window.matchMedia?.("(prefers-color-scheme: dark)").matches) {
      return "dark";
    }

    return "light";
  }

  setTheme(theme) {
    try {
      localStorage.setItem(STORAGE_KEY, theme);
    } catch (error) {}
  }

  toggleTheme(current) {
    return current === "dark" ? "light" : "dark";
  }
}
