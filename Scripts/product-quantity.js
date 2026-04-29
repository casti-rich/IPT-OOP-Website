const qtyValue = document.querySelector('.qty-value');
const increaseBtn = document.querySelector('.icon-btn:nth-of-type(1)');
const decreaseBtn = document.querySelector('.icon-btn:nth-of-type(2)');

increaseBtn.addEventListener('click', () => {
    qtyValue.textContent = parseInt(qtyValue.textContent) + 1;
});

decreaseBtn.addEventListener('click', () => {
    const currentQty = parseInt(qtyValue.textContent);
    if (currentQty > 0) {
        qtyValue.textContent = currentQty - 1;
    }
});
