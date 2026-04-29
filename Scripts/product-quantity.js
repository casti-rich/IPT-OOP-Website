document.querySelectorAll('.qty-controls, .actions-row').forEach((controls) => {
    const qtyValue = controls.querySelector('.qty-value');
    const quantityBtns = controls.querySelectorAll('.icon-btn');
    const increaseBtn = quantityBtns[0];
    const decreaseBtn = quantityBtns[1];
    const maxQty = Number.parseInt(controls.dataset.maxQty, 10);
    const hasMaxQty = Number.isFinite(maxQty);

    if (!qtyValue || !increaseBtn || !decreaseBtn) {
        return;
    }

    increaseBtn.addEventListener('click', () => {
        const currentQty = parseInt(qtyValue.textContent, 10) || 0;

        if (hasMaxQty && currentQty >= maxQty) {
            return;
        }

        qtyValue.textContent = String(currentQty + 1);
    });

    decreaseBtn.addEventListener('click', () => {
        const currentQty = parseInt(qtyValue.textContent, 10) || 0;
        if (currentQty > 0) {
            qtyValue.textContent = String(currentQty - 1);
        }
    });
});
