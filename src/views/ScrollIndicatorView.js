export class ScrollIndicatorView {
  constructor(element) {
    this.element = element;
  }

  update(progress) {
    if (!this.element) return;
    const clamped = Math.max(0, Math.min(1, progress));
    this.element.style.transform = `scaleX(${clamped})`;
  }
}
