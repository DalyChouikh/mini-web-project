export class RouterController {
  constructor(
    routerModel,
    articleModel,
    articleListView,
    articleDetailView,
    commentController
  ) {
    this.routerModel = routerModel;
    this.articleModel = articleModel;
    this.articleListView = articleListView;
    this.articleDetailView = articleDetailView;
    this.commentController = commentController;
  }

  init() {
    const initialRoute = this.routerModel.parseHash(window.location.hash);
    this.routerModel.setRoute(initialRoute.view, initialRoute.articleId);
    this.applyRoute(initialRoute);

    window.addEventListener("hashchange", () => {
      const route = this.routerModel.parseHash(window.location.hash);
      this.routerModel.setRoute(route.view, route.articleId);
      this.applyRoute(route);
    });
  }

  navigateToList() {
    window.location.hash = "";
  }

  navigateToArticle(id) {
    window.location.hash = `#article/${id}`;
  }

  applyRoute(route) {
    if (route.view === "list") {
      this.articleDetailView.hide();
      this.articleListView.show();
      window.scrollTo({ top: 0, behavior: "smooth" });
      return;
    }

    if (route.view === "detail" && route.articleId) {
      const article = this.articleModel.getById(route.articleId);
      if (!article) {
        this.articleDetailView.hide();
        this.articleListView.show();
        return;
      }
      this.articleListView.hide();
      this.articleDetailView.render(article);
      this.articleDetailView.show();
      this.commentController.showCommentsForArticle(article.id);
      window.scrollTo({ top: 0, behavior: "smooth" });
    }
  }
}
