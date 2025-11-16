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
    const route = this.routerModel.parseHash(window.location.hash);
    this.routerModel.setRoute(route.view, route.articleId);
    this.applyRoute(route);

    window.addEventListener("hashchange", () => {
      const newRoute = this.routerModel.parseHash(window.location.hash);
      this.routerModel.setRoute(newRoute.view, newRoute.articleId);
      this.applyRoute(newRoute);
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
        this.navigateToList();
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
