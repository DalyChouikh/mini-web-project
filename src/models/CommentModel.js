const STORAGE_KEY = "mini-blog-comments";

export class CommentModel {
  constructor() {
    this.commentsByArticleId = {};
    this.load();
  }

  load() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      if (raw) {
        this.commentsByArticleId = JSON.parse(raw);
      }
    } catch (error) {
      this.commentsByArticleId = {};
    }
  }

  save() {
    try {
      const raw = JSON.stringify(this.commentsByArticleId);
      localStorage.setItem(STORAGE_KEY, raw);
    } catch (error) {}
  }

  getComments(articleId) {
    return this.commentsByArticleId[articleId] || [];
  }

  addComment(articleId, { author, content }) {
    const trimmedAuthor = author.trim() || "Anonymous";
    const trimmedContent = content.trim();
    if (!trimmedContent) {
      return null;
    }
    const list = this.commentsByArticleId[articleId] || [];
    const comment = {
      id: `${Date.now()}-${Math.random().toString(16).slice(2)}`,
      author: trimmedAuthor,
      content: trimmedContent,
      createdAt: new Date().toISOString(),
    };
    list.push(comment);
    this.commentsByArticleId[articleId] = list;
    this.save();
    return comment;
  }
}
