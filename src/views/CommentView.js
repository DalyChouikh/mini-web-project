export class CommentView {
  constructor(container) {
    this.container = container;
  }

  render(comments) {
    if (!this.container) return;

    const commentsList =
      comments.length > 0
        ? `
      <ul class="comments-list">
        ${comments
          .map(
            (comment) => `
          <li class="comment">
            <div class="comment-header">
              <span class="comment-author">${comment.author}</span>
              <time class="comment-date" datetime="${comment.createdAt}">
                ${new Date(comment.createdAt).toLocaleString("en-US", {
                  month: "short",
                  day: "numeric",
                  year: "numeric",
                  hour: "2-digit",
                  minute: "2-digit",
                })}
              </time>
            </div>
            <p class="comment-body">${comment.content}</p>
          </li>
        `
          )
          .join("")}
      </ul>
    `
        : '<p style="color: var(--text-tertiary); font-size: 14px;">No comments yet. Be the first to share your thoughts!</p>';

    this.container.innerHTML = `
      <h2 class="comments-header">Comments (${comments.length})</h2>
      ${commentsList}
      <form class="comment-form">
        <div class="form-group">
          <label for="comment-name" class="form-label">Name</label>
          <input
            type="text"
            id="comment-name"
            class="form-input"
            placeholder="Your name"
            autocomplete="name"
          />
        </div>
        <div class="form-group">
          <label for="comment-text" class="form-label">Comment</label>
          <textarea
            id="comment-text"
            class="form-textarea"
            placeholder="Share your thoughts..."
            required
          ></textarea>
        </div>
        <button type="submit" class="form-submit">Post Comment</button>
      </form>
    `;
  }

  bindAddComment(handler) {
    if (!this.container) return;

    const form = this.container.querySelector(".comment-form");
    if (!form) return;

    form.addEventListener("submit", (e) => {
      e.preventDefault();

      const nameInput = form.querySelector("#comment-name");
      const textInput = form.querySelector("#comment-text");

      const author = nameInput?.value || "";
      const content = textInput?.value || "";

      handler({ author, content });

      if (nameInput) nameInput.value = "";
      if (textInput) textInput.value = "";
    });
  }
}
