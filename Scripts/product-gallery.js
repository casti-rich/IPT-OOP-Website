// Update the main preview and selected state when a thumbnail is clicked.
document.addEventListener('click', (event) => {
    const thumbButton = event.target.closest('.thumb');
    if (!thumbButton) {
        return;
    }

    const previewCard = thumbButton.closest('.preview-card');
    if (!previewCard) {
        return;
    }

    const mainImage = previewCard.querySelector('.main_thumb');
    const thumbImage = thumbButton.querySelector('img');
    if (!mainImage || !thumbImage) {
        return;
    }

    // Keep a single active thumbnail highlighted.
    previewCard.querySelectorAll('.thumb.is-active').forEach((thumb) => {
        thumb.classList.remove('is-active');
    });
    thumbButton.classList.add('is-active');

    // Swap the main preview to the chosen thumbnail.
    mainImage.src = thumbImage.src;
    mainImage.alt = thumbImage.alt.replace('thumbnail', '').trim();

    // Sync the hidden cart image input with the selected preview.
    const imageInput = document.querySelector('input[name="image"]');
    if (imageInput) {
        imageInput.value = thumbImage.src;
    }
});
