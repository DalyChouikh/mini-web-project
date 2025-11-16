export class AppController {
  constructor(options) {
    this.articleModel = options.articleModel;
    this.commentController = options.commentController;
    this.themeController = options.themeController;
    this.searchController = options.searchController;
    this.routerController = options.routerController;
    this.headerView = options.headerView;
    this.scrollIndicatorView = options.scrollIndicatorView;
    this.backToTopView = options.backToTopView;
    this.toastView = options.toastView;
    this.articleDetailView = options.articleDetailView;
  }

  async init() {
    this.themeController.init();
    this.themeController.bindToggle();

    this.headerView.bindSearch((query) => {
      this.searchController.handleSearch(query);
    });

    this.commentController.setValidationHandler((message) => {
      this.toastView.show(message, "error");
    });

    this.articleDetailView.bindShare((type) => {
      this.handleShare(type);
    });

    this.bindScrollFeatures();

    const result = await this.articleModel.loadFromApi();
    if (!result.ok) {
      const articles = this.articleModel.getAll();
      if (articles.length === 0) {
        this.toastView.show(
          "Using offline articles (API unavailable)",
          "error"
        );
      } else {
        this.toastView.show("Loaded local articles (API unavailable)", "info");
      }
    }

    const allArticles = this.articleModel.getAll();
    this.searchController.handleSearch("");

    this.routerController.init();
  }

  bindScrollFeatures() {
    window.addEventListener("scroll", () => {
      const scrollTop = window.scrollY || window.pageYOffset;
      const doc = document.documentElement;
      const docHeight = doc.scrollHeight - window.innerHeight;
      const progress = docHeight > 0 ? scrollTop / docHeight : 0;
      this.scrollIndicatorView.update(progress);

      if (scrollTop > 200) {
        this.backToTopView.show();
      } else {
        this.backToTopView.hide();
      }
    });

    this.backToTopView.bindClick(() => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }

  async handleShare(type) {
    const routeHash = window.location.hash;
    if (!routeHash.startsWith("#article/")) {
      this.toastView.show("Open an article before sharing.", "info");
      return;
    }
    const articleId = routeHash.replace("#article/", "");
    const url = `${window.location.origin}${window.location.pathname}#article/${articleId}`;

    if (type === "copy") {
      try {
        if (navigator.clipboard && navigator.clipboard.writeText) {
          await navigator.clipboard.writeText(url);
          this.toastView.show("Link copied to clipboard", "success");
        } else {
          this.toastView.show(
            "Clipboard not available in this browser.",
            "info"
          );
        }
      } catch (error) {
        this.toastView.show("Unable to copy link.", "error");
      }
      return;
    }

    if (type === "twitter") {
      this.toastView.show("Shared to Twitter (simulation)", "info");
      return;
    }

    if (type === "linkedin") {
      this.toastView.show("Shared to LinkedIn (simulation)", "info");
      return;
    }

    this.toastView.show("Sharing action (simulation)", "info");
  }
}
