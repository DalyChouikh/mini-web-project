export class ArticleDetailView {
  constructor(container, backButton) {
    this.container = container;
    this.backButton = backButton;
    this.detailSection = document.getElementById("detail-view");
  }

  render(article) {
    if (!this.container || !article) return;

    const formattedDate = new Date(article.date).toLocaleDateString("en-US", {
      year: "numeric",
      month: "long",
      day: "numeric",
    });

    const paragraphs = article.content
      .split("\n")
      .filter((p) => p.trim())
      .map((p) => `<p>${p.trim()}</p>`)
      .join("");

    this.container.innerHTML = `
      ${
        article.imageUrl
          ? `<img src="${article.imageUrl}" alt="" class="article-detail-image" />`
          : ""
      }
      <div class="article-detail-content">
        <header class="article-detail-header">
          <h1 class="article-detail-title">${article.title}</h1>
          <div class="article-detail-meta">
            <span>By ${article.author}</span>
            <span>•</span>
            <time datetime="${article.date}">${formattedDate}</time>
            <span>•</span>
            <span>${article.readTime} min read</span>
          </div>
        </header>
        <div class="article-detail-body">
          ${paragraphs}
        </div>
        <footer class="article-detail-footer">
          <div class="article-tags">
            ${article.tags
              .map((tag) => `<span class="tag">${tag}</span>`)
              .join("")}
          </div>
          <div class="share-buttons">
            <button class="share-button" data-action="copy">📋 Copy link</button>
            <button class="share-button" data-action="twitter">🐦 Twitter</button>
            <button class="share-button" data-action="linkedin">💼 LinkedIn</button>
          </div>
        </footer>
      </div>
    `;
  }

  bindBack(handler) {
    if (!this.backButton) return;
    this.backButton.addEventListener("click", handler);
  }

  bindShare(handler) {
    if (!this.container) return;
    this.container.addEventListener("click", (e) => {
      const button = e.target.closest(".share-button");
      if (button) {
        handler(button.dataset.action);
      }
    });
  }

  show() {
    if (this.detailSection) {
      this.detailSection.hidden = false;
    }
  }

  hide() {
    if (this.detailSection) {
      this.detailSection.hidden = true;
    }
  }
}
