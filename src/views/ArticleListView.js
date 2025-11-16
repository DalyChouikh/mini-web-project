export class ArticleListView {
  constructor(rootElement) {
    this.rootElement = rootElement;
    this.listViewSection = document.getElementById("list-view");
  }

  render(articles) {
    if (!this.rootElement) {
      return;
    }
    this.rootElement.innerHTML = "";
    articles.forEach((article) => {
      const card = document.createElement("article");
      card.className = "article-card";
      card.dataset.id = article.id;

      const header = document.createElement("header");
      header.className = "article-card-header";

      const title = document.createElement("h2");
      title.className = "article-card-title";
      title.textContent = article.title;

      const meta = document.createElement("div");
      meta.className = "article-card-meta";
      const time = document.createElement("time");
      time.dateTime = article.date;
      time.textContent = new Date(article.date).toLocaleDateString();
      const readTime = document.createElement("span");
      readTime.textContent = `${article.readTime} min read`;
      meta.appendChild(time);
      meta.appendChild(readTime);

      header.appendChild(title);
      header.appendChild(meta);

      const summary = document.createElement("p");
      summary.className = "article-card-summary";
      summary.textContent = article.summary;

      const footer = document.createElement("footer");
      footer.className = "article-card-footer";

      const tagsContainer = document.createElement("div");
      tagsContainer.className = "article-card-tags";
      (article.tags || []).forEach((tag) => {
        const tagEl = document.createElement("span");
        tagEl.className = "tag-pill";
        tagEl.textContent = tag;
        tagsContainer.appendChild(tagEl);
      });

      const button = document.createElement("button");
      button.type = "button";
      button.className = "article-card-button";
      button.textContent = "Read";

      footer.appendChild(tagsContainer);
      footer.appendChild(button);

      card.appendChild(header);
      card.appendChild(summary);
      card.appendChild(footer);

      this.rootElement.appendChild(card);
    });
  }

  bindArticleClick(handler) {
    if (!this.rootElement) {
      return;
    }
    this.rootElement.addEventListener("click", (event) => {
      const target = event.target;
      const card = target.closest("article.article-card");
      if (!card) {
        return;
      }
      const id = card.dataset.id;
      if (id) {
        handler(id);
      }
    });
  }

  show() {
    if (this.listViewSection) {
      this.listViewSection.hidden = false;
    }
  }

  hide() {
    if (this.listViewSection) {
      this.listViewSection.hidden = true;
    }
  }
}
