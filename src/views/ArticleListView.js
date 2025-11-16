export class ArticleListView {
  constructor(container) {
    this.container = container;
    this.listSection = document.getElementById("list-view");
  }

  render(articles) {
    if (!this.container) return;

    this.container.innerHTML = articles
      .map(
        (article) => `
      <article class="article-card" data-id="${article.id}">
        ${
          article.imageUrl
            ? `<img src="${article.imageUrl}" alt="" class="article-card-image" loading="lazy" />`
            : ""
        }
        <div class="article-card-content">
          <header class="article-card-header">
            <h2 class="article-card-title">${article.title}</h2>
            <div class="article-card-meta">
              <time datetime="${article.date}">
                ${new Date(article.date).toLocaleDateString("en-US", {
                  year: "numeric",
                  month: "short",
                  day: "numeric",
                })}
              </time>
              <span>•</span>
              <span>${article.readTime} min read</span>
            </div>
          </header>
          <p class="article-card-summary">${article.summary}</p>
          <footer class="article-card-footer">
            <div class="article-tags">
              ${article.tags
                .map((tag) => `<span class="tag">${tag}</span>`)
                .join("")}
            </div>
            <button class="read-button">Read →</button>
          </footer>
        </div>
      </article>
    `
      )
      .join("");
  }

  bindArticleClick(handler) {
    if (!this.container) return;
    this.container.addEventListener("click", (e) => {
      const card = e.target.closest(".article-card");
      if (card) {
        handler(card.dataset.id);
      }
    });
  }

  show() {
    if (this.listSection) {
      this.listSection.hidden = false;
    }
  }

  hide() {
    if (this.listSection) {
      this.listSection.hidden = true;
    }
  }
}
