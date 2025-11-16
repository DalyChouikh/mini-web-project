export class ThemeController {
  constructor(themeModel, headerView) {
    this.themeModel = themeModel;
    this.headerView = headerView;
    this.currentTheme = "light";
  }

  init() {
    const theme = this.themeModel.getTheme();
    this.applyTheme(theme);
    this.headerView.updateThemeIcon(theme);
  }

  bindToggle() {
    this.headerView.bindThemeToggle(() => {
      const next = this.themeModel.toggleTheme(this.currentTheme);
      this.applyTheme(next);
      this.themeModel.setTheme(next);
      this.headerView.updateThemeIcon(next);
    });
  }

  applyTheme(theme) {
    this.currentTheme = theme;
    document.documentElement.setAttribute("data-theme", theme);
  }
}
