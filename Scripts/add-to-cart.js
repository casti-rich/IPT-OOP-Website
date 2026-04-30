document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form.actions-row').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            var qtySpan = form.querySelector('.qty-value');
            var qtyInput = form.querySelector('.cart-qty-input');
            var qty = parseInt((qtySpan && qtySpan.textContent) ? qtySpan.textContent.trim() : '0', 10) || 0;
            if (qtyInput) qtyInput.value = String(qty);
            if (qty <= 0) {
                e.preventDefault();
                alert('Please select a quantity greater than 0');
            }
        });
    });
});
