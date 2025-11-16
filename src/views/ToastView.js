export class ToastView {
  constructor(element) {
    this.element = element;
    this.timeout = null;
  }

  show(message, type = "info", duration = 3000) {
    if (!this.element) return;

    if (this.timeout) {
      clearTimeout(this.timeout);
    }

    this.element.textContent = message;
    this.element.className = `toast visible ${type}`;

    this.timeout = setTimeout(() => {
      this.hide();
    }, duration);
  }

  hide() {
    if (!this.element) return;
    this.element.classList.remove("visible");
  }
}
