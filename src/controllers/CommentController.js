export class CommentController {
  constructor(commentModel, commentView, toastView) {
    this.commentModel = commentModel;
    this.commentView = commentView;
    this.toastView = toastView;
    this.currentArticleId = null;
  }

  showCommentsForArticle(articleId) {
    this.currentArticleId = articleId;
    const comments = this.commentModel.getComments(articleId);
    this.commentView.render(comments);
    this.commentView.bindAddComment((data) => this.handleAddComment(data));
  }

  handleAddComment({ author, content }) {
    if (!this.currentArticleId) return;

    const comment = this.commentModel.addComment(this.currentArticleId, {
      author,
      content,
    });

    if (!comment) {
      this.toastView.show("Please write a comment before posting", "error");
      return;
    }

    const comments = this.commentModel.getComments(this.currentArticleId);
    this.commentView.render(comments);
    this.commentView.bindAddComment((data) => this.handleAddComment(data));
    this.toastView.show("Comment posted successfully!", "success");
  }
}
