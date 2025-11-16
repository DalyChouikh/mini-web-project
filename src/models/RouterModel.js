export class RouterModel {
  constructor() {
    this.currentRoute = { view: "list", articleId: null };
  }

  parseHash(hash) {
    const cleanHash = hash.replace(/^#\/?/, "");

    if (!cleanHash) {
      return { view: "list", articleId: null };
    }

    const match = cleanHash.match(/^article\/(.+)$/);
    if (match) {
      return { view: "detail", articleId: match[1] };
    }

    return { view: "list", articleId: null };
  }

  getRoute() {
    return { ...this.currentRoute };
  }

  setRoute(view, articleId = null) {
    this.currentRoute = { view, articleId };
  }
}
