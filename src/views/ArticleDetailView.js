export class ArticleDetailView {
  constructor(detailContainer, backButton) {
    this.detailContainer = detailContainer;
    this.backButton = backButton;
    this.detailViewSection = document.getElementById("detail-view");
  }

  render(article) {
    if (!this.detailContainer || !article) {
      return;
    }
    const date = new Date(article.date);
    const dateLabel = date.toLocaleDateString();

    const tagsHtml = (article.tags || [])
      .map((tag) => `<span class="tag-pill">${tag}</span>`)
      .join("");

    const imageHtml = article.imageUrl
      ? `<img src="${article.imageUrl}" alt="" />`
      : "";

    const paragraphs = String(article.content)
      .split("\n")
      .filter((p) => p.trim().length > 0)
      .map((p) => `<p>${p.trim()}</p>`)
      .join("");

    this.detailContainer.innerHTML = `
      <article>
        <header class="article-detail-header">
          <div>
            <h2 class="article-detail-title">${article.title}</h2>
            <div class="article-detail-meta">
              <time datetime="${article.date}">${dateLabel}</time>
              <span>${article.readTime} min read</span>
              <span>By ${article.author}</span>
            </div>
          </div>
          ${imageHtml}
        </header>
        <section class="article-detail-content">
          ${paragraphs}
        </section>
        <footer class="article-detail-footer">
          <div class="article-card-tags">${tagsHtml}</div>
          <div class="article-detail-share">
            <button type="button" class="share-button" data-share="copy">Copy link</button>
            <button type="button" class="share-button" data-share="twitter">Share on Twitter</button>
            <button type="button" class="share-button" data-share="linkedin">Share on LinkedIn</button>
          </div>
        </footer>
      </article>
    `;
  }

  bindBack(handler) {
    if (!this.backButton) {
      return;
    }
    this.backButton.addEventListener("click", () => {
      handler();
    });
  }

  bindShare(handler) {
    if (!this.detailContainer) {
      return;
    }
    this.detailContainer.addEventListener("click", (event) => {
      const target = event.target;
      if (!(target instanceof HTMLElement)) {
        return;
      }
      const button = target.closest("button.share-button");
      if (!button) {
        return;
      }
      const type = button.dataset.share;
      if (type) {
        handler(type);
      }
    });
  }

  show() {
    if (this.detailViewSection) {
      this.detailViewSection.hidden = false;
    }
  }

  hide() {
    if (this.detailViewSection) {
      this.detailViewSection.hidden = true;
    }
  }
}
