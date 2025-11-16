export class CommentView {
  constructor(rootElement) {
    this.rootElement = rootElement;
  }

  renderComments(comments) {
    if (!this.rootElement) {
      return;
    }

    const itemsHtml = comments
      .map((comment) => {
        const date = new Date(comment.createdAt);
        const label = date.toLocaleString();
        return `
          <li class="comment-item">
            <div class="comment-meta">
              <span>${comment.author}</span>
              <time datetime="${comment.createdAt}">${label}</time>
            </div>
            <p class="comment-content">${comment.content}</p>
          </li>
        `;
      })
      .join("");

    this.rootElement.innerHTML = `
      <h2 class="comments-header">Comments</h2>
      <ul class="comment-list">${itemsHtml}</ul>
      <form class="comment-form">
        <div class="comment-form-row">
          <label for="comment-author">Name</label>
          <input id="comment-author" class="comment-input" type="text" placeholder="Your name" />
        </div>
        <div class="comment-form-row">
          <label for="comment-content">Comment</label>
          <textarea id="comment-content" class="comment-textarea" placeholder="Share your thoughts"></textarea>
        </div>
        <button type="submit" class="comment-submit">Post comment</button>
      </form>
    `;
  }

  bindAddComment(handler) {
    if (!this.rootElement) {
      return;
    }
    const form = this.rootElement.querySelector(".comment-form");
    if (!form) {
      return;
    }
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      const authorInput = this.rootElement.querySelector("#comment-author");
      const contentInput = this.rootElement.querySelector("#comment-content");
      const author = authorInput ? authorInput.value : "";
      const content = contentInput ? contentInput.value : "";
      handler({ author, content });
      if (authorInput) {
        authorInput.value = "";
      }
      if (contentInput) {
        contentInput.value = "";
      }
    });
  }
}
