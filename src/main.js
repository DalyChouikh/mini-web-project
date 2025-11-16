import { ApiClient } from "./data/apiClient.js";
import { localArticles } from "./data/localArticles.js";

import { ArticleModel } from "./models/ArticleModel.js";
import { CommentModel } from "./models/CommentModel.js";
import { ThemeModel } from "./models/ThemeModel.js";
import { RouterModel } from "./models/RouterModel.js";

import { HeaderView } from "./views/HeaderView.js";
import { ArticleListView } from "./views/ArticleListView.js";
import { ArticleDetailView } from "./views/ArticleDetailView.js";
import { CommentView } from "./views/CommentView.js";
import { ScrollIndicatorView } from "./views/ScrollIndicatorView.js";
import { BackToTopView } from "./views/BackToTopView.js";
import { ToastView } from "./views/ToastView.js";

import { SearchController } from "./controllers/SearchController.js";
import { CommentController } from "./controllers/CommentController.js";
import { ThemeController } from "./controllers/ThemeController.js";
import { RouterController } from "./controllers/RouterController.js";
import { AppController } from "./controllers/AppController.js";

const apiClient = new ApiClient();
const articleModel = new ArticleModel(localArticles, apiClient);
const commentModel = new CommentModel();
const themeModel = new ThemeModel();
const routerModel = new RouterModel();

const headerView = new HeaderView(
  document.getElementById("search-input"),
  document.getElementById("theme-toggle")
);

const articleListView = new ArticleListView(
  document.getElementById("article-list")
);
const articleDetailView = new ArticleDetailView(
  document.getElementById("article-detail"),
  document.getElementById("back-to-list")
);
const commentView = new CommentView(
  document.getElementById("comments-section")
);
const scrollIndicatorView = new ScrollIndicatorView(
  document.querySelector(".scroll-indicator-bar")
);
const backToTopView = new BackToTopView(document.getElementById("back-to-top"));
const toastView = new ToastView(document.getElementById("toast"));

const searchController = new SearchController(articleModel, articleListView);
const commentController = new CommentController(commentModel, commentView);
const themeController = new ThemeController(themeModel, headerView);
const routerController = new RouterController(
  routerModel,
  articleModel,
  articleListView,
  articleDetailView,
  commentController
);

const appController = new AppController({
  articleModel,
  commentController,
  themeController,
  searchController,
  routerController,
  headerView,
  scrollIndicatorView,
  backToTopView,
  toastView,
  articleDetailView,
});

articleListView.bindArticleClick((id) => {
  routerController.navigateToArticle(id);
});

articleDetailView.bindBack(() => {
  routerController.navigateToList();
});

appController.init();
