export class ScrollIndicatorView {
  constructor(barElement) {
    this.barElement = barElement;
  }

  update(progress) {
    if (!this.barElement) {
      return;
    }
    const clamped = Math.max(0, Math.min(1, progress));
    this.barElement.style.width = `${clamped * 100}%`;
  }
}
