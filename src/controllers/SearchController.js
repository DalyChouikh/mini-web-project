export class SearchController {
  constructor(articleModel, articleListView) {
    this.articleModel = articleModel;
    this.articleListView = articleListView;
  }

  handleSearch(query) {
    const results = this.articleModel.search(query);
    this.articleListView.render(results);
  }
}
