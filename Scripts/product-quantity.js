// Quantity increment/decrement controls
document.addEventListener('click', (event) => {
    const button = event.target.closest('.icon-btn');
    if (!button) {
        return;
    }

    const controls = button.closest('.actions-row') || button.closest('.qty-controls');
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
    const clampedQty = Math.max(0, nextQty);
    qtyValue.textContent = String(clampedQty);

    // Update checkout sidebar totals when present.
    if (controls.classList.contains('qty-controls')) {
        const title = controls.dataset.title;
        if (title) {
            const panelItem = document.querySelector(`.panel-cart-item[data-title="${CSS.escape(title)}"]`);
            const panelQty = panelItem?.querySelector('.panel-item-qty');
            if (panelQty) {
                panelQty.textContent = `Qty: ${clampedQty}`;
            }
        }

        const allControls = document.querySelectorAll('.qty-controls[data-price]');
        let itemCount = 0;
        let subtotal = 0;

        allControls.forEach((control) => {
            const qty = parseInt(control.querySelector('.qty-value')?.textContent || '0', 10) || 0;
            const price = Number.parseFloat(control.dataset.price || '0');
            itemCount += qty;
            subtotal += price * qty;
        });

        const qtyEl = document.getElementById('summary-qty');
        const subtotalEl = document.getElementById('summary-subtotal');
        const shippingEl = document.getElementById('summary-shipping');
        const totalEl = document.getElementById('summary-total');
        const shippingValue = Number.parseFloat(shippingEl?.dataset.value || '0');
        const total = subtotal + (Number.isFinite(shippingValue) ? shippingValue : 0);

        if (qtyEl) {
            qtyEl.textContent = String(itemCount);
        }
        if (subtotalEl) {
            subtotalEl.textContent = `$ ${subtotal.toFixed(2)}`;
        }
        if (totalEl) {
            totalEl.textContent = `$ ${total.toFixed(2)}`;
        }
    }
});