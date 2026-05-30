// Quantity increment/decrement controls
document.addEventListener('click', (event) => {
    const button = event.target.closest('.icon-btn');
    if (!button) {
        return;
    }

    const controls = button.closest('.actions-row');
    if (!controls) {
        return;
    }

    const qtyValue = controls.querySelector('.qty-value');
    if (!qtyValue) {
        return;
    }

    const maxQty = Number.parseInt(controls.dataset.maxQty, 10);
    const hasMaxQty = Number.isFinite(maxQty);
    const currentQty = parseInt(qtyValue.textContent, 10) || 0;
    const delta = button.textContent.trim() === '+' ? 1 : -1;
    const nextQty = currentQty + delta;

    // Prevent increments beyond inventory when a max is provided.
    if (delta > 0 && hasMaxQty && currentQty >= maxQty) {
        return;
    }

    // Clamp the displayed quantity at zero.
    qtyValue.textContent = String(Math.max(0, nextQty));
});
