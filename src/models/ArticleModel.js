export class ArticleModel {
  constructor(initialArticles = [], apiClient = null) {
    this.articles = initialArticles;
    this.apiClient = apiClient;
  }

  async loadFromApi() {
    if (!this.apiClient) {
      return { success: false };
    }
    try {
      const rawArticles = await this.apiClient.fetchArticles();
      this.articles = rawArticles.map((item) => this.mapFromApi(item));
      return { success: true };
    } catch (error) {
      return { success: false, error };
    }
  }

  loadLocal(articles) {
    this.articles = articles;
  }

  getAll() {
    return [...this.articles];
  }

  getById(id) {
    return this.articles.find((article) => article.id === String(id)) || null;
  }

  search(query) {
    const term = query.trim().toLowerCase();
    if (!term) {
      return this.getAll();
    }
    return this.articles.filter((article) => {
      const searchText = [
        article.title,
        article.summary,
        article.content,
        article.author,
        ...(article.tags || []),
      ]
        .join(" ")
        .toLowerCase();
      return searchText.includes(term);
    });
  }

  mapFromApi(item) {
    const tagsRaw = item.tags || "";
    const tags = Array.isArray(tagsRaw)
      ? tagsRaw
      : String(tagsRaw)
          .split(",")
          .map((t) => t.trim())
          .filter(Boolean);

    return {
      id: String(item.id),
      title: item.title || "Untitled",
      summary: item.summary || "",
      content: item.content || "",
      tags,
      date: this.normalizeDate(item.createdAt),
      readTime: typeof item.readTime === "number" ? item.readTime : 5,
      author: item.author || "Anonymous",
      imageUrl: item.imageUrl || "",
    };
  }

  normalizeDate(raw) {
    if (!raw) return new Date().toISOString();
    if (typeof raw === "string") return new Date(raw).toISOString();
    const timestamp = raw < 10000000000 ? raw * 1000 : raw;
    return new Date(timestamp).toISOString();
  }
}
