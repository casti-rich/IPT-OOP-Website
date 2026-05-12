<?php
require_once __DIR__ . '/data/products.php';

$productKey = $_GET['key'] ?? '';
$product = $products[$productKey] ?? null;

if ($product === null) {
    http_response_code(404);
}

$images = $product ? $product->imagesByView : [];
$mainImage = $images['img1'] ?? (count($images) ? array_values($images)[0] : '');

$thumbImages = [];
foreach ($images as $key => $value) {
    if ($key !== 'img1') {
        $thumbImages[] = $value;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Viewing Page</title>
    <link rel="stylesheet" href="CSS/products-navbar.css">
    <link rel="stylesheet" href="CSS/product-viewing-page.css">
</head>
<body>
    <?php include 'products-navbar.php'; ?>

    <?php if ($product === null): ?>
        <main class="page-wrap" aria-label="Product viewing layout">
            <section class="product-details" aria-label="Product information">
                <h1 class="product-title">Product not found</h1>
                <p class="product-description">The product you are looking for does not exist.</p>
                <p><a href="product_list.php">Return to products</a></p>
            </section>
        </main>
    <?php else: ?>
        <main class="page-wrap" aria-label="Product viewing layout">
            <section class="product-preview" aria-label="Product image preview">
                <div class="preview-large">
                    <img src="<?= htmlspecialchars($mainImage) ?>" alt="<?= htmlspecialchars($product->title) ?>" class="main_thumb">
                </div>
                <div class="preview-thumbs">
                    <?php foreach ($thumbImages as $index => $thumb): ?>
                        <div class="thumb">
                            <img src="<?= htmlspecialchars($thumb) ?>" alt="<?= htmlspecialchars($product->title) ?> thumbnail <?= htmlspecialchars((string) ($index + 2)) ?>" class="sub_thumb">
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="product-details" aria-label="Product information">
                <h1 class="product-title"><?= htmlspecialchars($product->title) ?></h1>
                <p class="product-description">
                    <?= htmlspecialchars($product->description) ?>
                </p>
                <?php if (property_exists($product, 'numberOfKeys')): ?>
                    <p class="product-description">
                        Type: <?= htmlspecialchars($product->numberOfKeys) ?>-Key Electric Piano
                    </p>
                <?php endif; ?>
                <p class="product-price">$ <?= htmlspecialchars($product->price) ?></p>
                <p class="product-inventory">Stocks: <?= htmlspecialchars($product->inventory) ?></p>

                <form method="post" action="add_to_cart.php" class="actions-row" aria-label="Quantity and add to cart" data-max-qty="<?= htmlspecialchars((string) $product->inventory) ?>">
                    <button type="button" class="icon-btn" aria-label="Increase quantity">+</button>
                    <span class="qty-value">0</span>
                    <button type="button" class="icon-btn" aria-label="Decrease quantity">-</button>
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product->id) ?>">
                    <input type="hidden" name="product_key" value="<?= htmlspecialchars($productKey) ?>">
                    <input type="hidden" name="inventory" value="<?= htmlspecialchars((string) $product->inventory) ?>">
                    <input type="hidden" name="title" value="<?= htmlspecialchars($product->title) ?>">
                    <input type="hidden" name="price" value="<?= htmlspecialchars($product->price) ?>">
                    <input type="hidden" name="image" value="<?= htmlspecialchars($mainImage) ?>">
                    <input type="hidden" name="quantity" value="0" class="cart-qty-input">
                    <button type="submit" class="cta-btn">Add to cart</button>
                </form>
            </section>

            <div class="container">
                <h1><?= htmlspecialchars($product->title) ?></h1>
                <div class="rating">
                    <span id="rating">0</span>/5
                </div>
                <div class="stars" id="stars">
                    <span class="star" data-value="1">★</span>
                    <span class="star" data-value="2">★</span>
                    <span class="star" data-value="3">★</span>
                    <span class="star" data-value="4">★</span>
                    <span class="star" data-value="5">★</span>
                </div>
                <p>Share your review:</p>
                <textarea id="review" placeholder="Write your review here"></textarea>

                <div class="review-upload">
                    <label for="review-image">Attach an image to your review</label>
                    <input type="file" id="review-image" accept="image/*">
                </div>

                <button class="submit-btn" id="submit">Submit</button>
                <div class="reviews" id="reviews"></div>
            </div>
        </main>
    <?php endif; ?>

    <script src="Scripts/product-quantity.js"></script>
    <script src="Scripts/product-review.js"></script>
    <script src="Scripts/add-to-cart.js"></script>
</body>
</html>
