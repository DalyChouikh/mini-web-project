export class BackToTopView {
  constructor(buttonElement) {
    this.buttonElement = buttonElement;
  }

  bindClick(handler) {
    if (!this.buttonElement) {
      return;
    }
    this.buttonElement.addEventListener("click", () => {
      handler();
    });
  }

  show() {
    if (this.buttonElement) {
      this.buttonElement.classList.add("visible");
    }
  }

  hide() {
    if (this.buttonElement) {
      this.buttonElement.classList.remove("visible");
    }
  }
}
