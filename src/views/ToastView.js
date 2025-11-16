export class ToastView {
  constructor(rootElement) {
    this.rootElement = rootElement;
    this.hideTimeoutId = null;
  }

  show(message, type = "info", duration = 2600) {
    if (!this.rootElement) {
      return;
    }
    this.rootElement.textContent = message;
    this.rootElement.classList.remove(
      "toast-info",
      "toast-success",
      "toast-error"
    );
    if (type === "success") {
      this.rootElement.classList.add("toast-success");
    } else if (type === "error") {
      this.rootElement.classList.add("toast-error");
    } else {
      this.rootElement.classList.add("toast-info");
    }
    this.rootElement.hidden = false;
    this.rootElement.classList.add("visible");
    if (this.hideTimeoutId) {
      window.clearTimeout(this.hideTimeoutId);
    }
    this.hideTimeoutId = window.setTimeout(() => {
      this.hide();
    }, duration);
  }

  hide() {
    if (!this.rootElement) {
      return;
    }
    this.rootElement.classList.remove("visible");
    this.rootElement.hidden = true;
  }
}
