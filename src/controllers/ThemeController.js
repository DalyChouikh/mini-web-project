export class ThemeController {
  constructor(themeModel, headerView) {
    this.themeModel = themeModel;
    this.headerView = headerView;
    this.currentTheme = "light";
  }

  init() {
    this.currentTheme = this.themeModel.getTheme();
    this.applyTheme(this.currentTheme);
    this.headerView.updateThemeIcon(this.currentTheme);
  }

  bindToggle() {
    this.headerView.bindThemeToggle(() => {
      const newTheme = this.themeModel.toggleTheme(this.currentTheme);
      this.applyTheme(newTheme);
      this.themeModel.setTheme(newTheme);
      this.headerView.updateThemeIcon(newTheme);
      this.currentTheme = newTheme;
    });
  }

  applyTheme(theme) {
    document.documentElement.setAttribute("data-theme", theme);
  }
}
