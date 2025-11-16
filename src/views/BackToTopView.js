export class BackToTopView {
  constructor(button) {
    this.button = button;
  }

  bindClick(handler) {
    if (!this.button) return;
    this.button.addEventListener("click", handler);
  }

  show() {
    if (this.button) {
      this.button.classList.add("visible");
    }
  }

  hide() {
    if (this.button) {
      this.button.classList.remove("visible");
    }
  }
}
