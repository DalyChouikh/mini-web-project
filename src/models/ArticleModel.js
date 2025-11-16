export class ArticleModel {
  constructor(initialArticles = [], apiClient = null) {
    this.articles = initialArticles;
    this.apiClient = apiClient;
  }

  async loadFromApi() {
    if (!this.apiClient) {
      return { ok: false };
    }
    try {
      const rawArticles = await this.apiClient.fetchArticles();
      this.articles = rawArticles.map((item) => this.mapFromApi(item));
      return { ok: true };
    } catch (error) {
      return { ok: false };
    }
  }

  loadLocal(articles) {
    this.articles = articles;
  }

  getAll() {
    return [...this.articles];
  }

  getById(id) {
    return this.articles.find((article) => article.id === id) || null;
  }

  search(query) {
    const value = query.trim().toLowerCase();
    if (!value) {
      return this.getAll();
    }
    return this.articles.filter((article) => {
      const text = `${article.title} ${article.summary} ${article.content} ${(
        article.tags || []
      ).join(" ")}`.toLowerCase();
      return text.includes(value);
    });
  }

  mapFromApi(item) {
    const tagsString = item.tags || "";
    const tags = Array.isArray(tagsString)
      ? tagsString
      : String(tagsString)
          .split(",")
          .map((tag) => tag.trim())
          .filter(Boolean);
    return {
      id: String(item.id),
      title: item.title || "Untitled",
      summary: item.summary || "",
      content: item.content || "",
      tags,
      date: this.normalizeDate(item.createdAt),
      readTime: typeof item.readTime === "number" ? item.readTime : 5,
      author: item.author || "Unknown",
      imageUrl: item.imageUrl || "",
    };
  }

  normalizeDate(raw) {
    if (!raw) {
      return new Date().toISOString();
    }
    if (typeof raw === "number") {
      const millis = raw < 10_000_000_000 ? raw * 1000 : raw;
      return new Date(millis).toISOString();
    }
    return new Date(raw).toISOString();
  }
}
