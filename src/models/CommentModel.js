const STORAGE_KEY = "blog-comments";

export class CommentModel {
  constructor() {
    this.commentsByArticle = {};
    this.load();
  }

  load() {
    try {
      const data = localStorage.getItem(STORAGE_KEY);
      if (data) {
        this.commentsByArticle = JSON.parse(data);
      }
    } catch (error) {
      this.commentsByArticle = {};
    }
  }

  save() {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(this.commentsByArticle));
    } catch (error) {}
  }

  getComments(articleId) {
    return this.commentsByArticle[articleId] || [];
  }

  addComment(articleId, { author, content }) {
    const trimmedAuthor = author.trim() || "Anonymous";
    const trimmedContent = content.trim();

    if (!trimmedContent) {
      return null;
    }

    const comment = {
      id: `${Date.now()}-${Math.random().toString(36).slice(2)}`,
      author: trimmedAuthor,
      content: trimmedContent,
      createdAt: new Date().toISOString(),
    };

    if (!this.commentsByArticle[articleId]) {
      this.commentsByArticle[articleId] = [];
    }

    this.commentsByArticle[articleId].push(comment);
    this.save();
    return comment;
  }
}
