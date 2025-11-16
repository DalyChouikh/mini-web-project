export class AppController {
  constructor({
    articleModel,
    searchController,
    commentController,
    themeController,
    routerController,
    scrollIndicatorView,
    backToTopView,
    toastView,
    articleDetailView,
  }) {
    this.articleModel = articleModel;
    this.searchController = searchController;
    this.commentController = commentController;
    this.themeController = themeController;
    this.routerController = routerController;
    this.scrollIndicatorView = scrollIndicatorView;
    this.backToTopView = backToTopView;
    this.toastView = toastView;
    this.articleDetailView = articleDetailView;
  }

  async init() {
    this.themeController.init();
    this.themeController.bindToggle();

    const result = await this.articleModel.loadFromApi();

    if (!result.success) {
      const hasLocalArticles = this.articleModel.getAll().length > 0;
      if (hasLocalArticles) {
        this.toastView.show("Using offline articles", "info");
      } else {
        this.toastView.show("Failed to load articles", "error");
      }
    }

    this.searchController.handleSearch("");
    this.routerController.init();
    this.bindScrollFeatures();
    this.bindShareActions();
  }

  bindScrollFeatures() {
    window.addEventListener("scroll", () => {
      const scrollTop =
        window.pageYOffset || document.documentElement.scrollTop;
      const scrollHeight = document.documentElement.scrollHeight;
      const clientHeight = document.documentElement.clientHeight;
      const maxScroll = scrollHeight - clientHeight;

      const progress = maxScroll > 0 ? scrollTop / maxScroll : 0;
      this.scrollIndicatorView.update(progress);

      if (scrollTop > 300) {
        this.backToTopView.show();
      } else {
        this.backToTopView.hide();
      }
    });

    this.backToTopView.bindClick(() => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }

  bindShareActions() {
    this.articleDetailView.bindShare(async (action) => {
      const hash = window.location.hash;
      if (!hash.startsWith("#article/")) return;

      const articleId = hash.replace("#article/", "");
      const url = `${window.location.origin}${window.location.pathname}#article/${articleId}`;

      if (action === "copy") {
        try {
          await navigator.clipboard.writeText(url);
          this.toastView.show("Link copied to clipboard!", "success");
        } catch (error) {
          this.toastView.show("Failed to copy link", "error");
        }
      } else if (action === "twitter") {
        this.toastView.show("Shared to Twitter (simulation)", "info");
      } else if (action === "linkedin") {
        this.toastView.show("Shared to LinkedIn (simulation)", "info");
      }
    });
  }
}
