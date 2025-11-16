export class CommentController {
  constructor(commentModel, commentView) {
    this.commentModel = commentModel;
    this.commentView = commentView;
    this.currentArticleId = null;
    this.onValidationError = null;
  }

  setValidationHandler(handler) {
    this.onValidationError = handler;
  }

  showCommentsForArticle(articleId) {
    this.currentArticleId = articleId;
    const comments = this.commentModel.getComments(articleId);
    this.commentView.renderComments(comments);
    this.commentView.bindAddComment((payload) => {
      this.handleAddComment(payload);
    });
  }

  handleAddComment({ author, content }) {
    if (!this.currentArticleId) {
      return;
    }
    const result = this.commentModel.addComment(this.currentArticleId, {
      author,
      content,
    });
    if (!result) {
      if (this.onValidationError) {
        this.onValidationError("Please enter a comment before posting.");
      }
      return;
    }
    const comments = this.commentModel.getComments(this.currentArticleId);
    this.commentView.renderComments(comments);
    this.commentView.bindAddComment((payload) => {
      this.handleAddComment(payload);
    });
  }
}
