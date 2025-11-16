export class RouterModel {
  constructor() {
    this.route = { view: "list", articleId: null };
  }

  parseHash(hash) {
    if (!hash || hash === "#" || hash === "#/") {
      return { view: "list", articleId: null };
    }
    const value = hash.replace(/^#/, "");
    const parts = value.split("/");
    if (parts[0] === "article" && parts[1]) {
      return { view: "detail", articleId: parts[1] };
    }
    return { view: "list", articleId: null };
  }

  getRoute() {
    return { ...this.route };
  }

  setRoute(view, articleId = null) {
    this.route = { view, articleId };
    return this.getRoute();
  }
}
