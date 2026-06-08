<?php
// Load product data from the database instead of data/products.php
require_once __DIR__ . '/database/db.php';

$product = null;
$mainImage = '';
$thumbImages = [];

$requestedId = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($requestedId) {
    $sql = "SELECT p.*, pi.Stock AS inventory, p.Product_Image_Path FROM products p LEFT JOIN product_inventory pi ON p.Product_ID = pi.Product_ID WHERE p.Product_ID = " . intval($requestedId) . " LIMIT 1";
    $res = mysqli_query($conn, $sql);
    if ($res && $row = mysqli_fetch_assoc($res)) {
        $product = (object) [
            'id' => (int)$row['Product_ID'],
            'title' => $row['Product_Name'],
            'description' => $row['Product_Desc'],
            'price' => (float)$row['Product_Price'],
            'inventory' => isset($row['inventory']) ? (int)$row['inventory'] : 0,
            'imagesByView' => [],
        ];
        $img = $row['Product_Image_Path'] ?? null;
        if ($img) $product->imagesByView['img1'] = $img;
    }
}

// expose a productKey for legacy cart code (will be product id)
if ($product !== null) {
    $productKey = (string)$product->id;
}

if ($product === null) {
    http_response_code(404);
} else {
    $images = $product->imagesByView ?? [];
    $mainImage = $images['img1'] ?? (count($images) ? array_values($images)[0] : '');
    $thumbImages = [];
    foreach ($images as $k => $v) {
        if ($k !== 'img1') $thumbImages[] = $v;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Viewing Page</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/products-navbar.css">
    <link rel="stylesheet" href="CSS/product-viewing-page.css">
</head>
<body>
    <?php include 'products-navbar.php'; ?>

    <?php if ($product === null): ?>
        <main class="page-shell container py-5" aria-label="Product viewing layout">
            <section class="empty-state" aria-label="Product information">
                <h1 class="page-title">Product not found</h1>
                <p class="page-lead">The product you are looking for does not exist.</p>
                <a href="product_list.php" class="btn btn-accent">Return to products</a>
            </section>
        </main>
    <?php else: ?>
        <main class="page-shell container py-4" aria-label="Product viewing layout">
            <div class="row g-4">
                <section class="col-12 col-lg-6" aria-label="Product image preview">
                    <div class="preview-card">
                        <div class="preview-large">
                            <img src="<?= htmlspecialchars($mainImage) ?>" alt="<?= htmlspecialchars($product->title) ?>" class="main_thumb" id="main-image">
                        </div>
                        <?php if (!empty($thumbImages)): ?>
                            <div class="preview-thumbs">
                                <?php foreach ($thumbImages as $index => $thumb): ?>
                                    <button type="button" class="thumb" aria-label="Preview thumbnail <?= htmlspecialchars((string) ($index + 2)) ?>">
                                        <img src="<?= htmlspecialchars($thumb) ?>" alt="<?= htmlspecialchars($product->title) ?> thumbnail <?= htmlspecialchars((string) ($index + 2)) ?>" class="sub_thumb">
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <section class="col-12 col-lg-6" aria-label="Product information">
                    <div class="details-card">
                        <h1 class="page-title mb-3"><?= htmlspecialchars($product->title) ?></h1>
                        <p class="page-lead">
                            <?= htmlspecialchars($product->description) ?>
                        </p>
                        <?php if (property_exists($product, 'numberOfKeys')): ?>
                            <p class="page-lead">Type: <?= htmlspecialchars($product->numberOfKeys) ?>-Key Electric Piano</p>
                            <p class="product-keys">Keys: <?= htmlspecialchars($product->numberOfKeys) ?></p>
                        <?php endif; ?>
                        <p class="product-price">$ <?= htmlspecialchars($product->price) ?></p>
                        <p class="product-inventory">Stocks: <?= htmlspecialchars($product->inventory) ?></p>

                        <form method="post" action="add_to_cart.php" class="actions-row" aria-label="Quantity and add to cart" data-max-qty="<?= htmlspecialchars((string) $product->inventory) ?>">
                            <div class="qty-controls">
                                <button type="button" class="icon-btn" aria-label="Increase quantity">+</button>
                                <span class="qty-value">0</span>
                                <button type="button" class="icon-btn" aria-label="Decrease quantity">-</button>
                            </div>
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product->id) ?>">
                            <input type="hidden" name="product_key" value="<?= htmlspecialchars($productKey) ?>">
                            <input type="hidden" name="inventory" value="<?= htmlspecialchars((string) $product->inventory) ?>">
                            <input type="hidden" name="title" value="<?= htmlspecialchars($product->title) ?>">
                            <input type="hidden" name="price" value="<?= htmlspecialchars($product->price) ?>">
                            <input type="hidden" name="image" value="<?= htmlspecialchars($mainImage) ?>">
                            <input type="hidden" name="quantity" value="0" class="cart-qty-input">
                            <button type="submit" class="btn btn-accent">Add to cart</button>
                        </form>
                    </div>
                </section>

                <section class="col-12" aria-label="Product reviews">
                    <div class="review-panel">
                        <div class="review-header">
                            <h2 class="review-title"><?= htmlspecialchars($product->title) ?></h2>
                            <div class="rating"><span id="rating">0</span>/5</div>
                        </div>
                        <div class="stars" id="stars">
                            <span class="star" data-value="1">★</span>
                            <span class="star" data-value="2">★</span>
                            <span class="star" data-value="3">★</span>
                            <span class="star" data-value="4">★</span>
                            <span class="star" data-value="5">★</span>
                        </div>
                        <label for="review" class="form-label mt-3">Share your review</label>
                        <textarea id="review" class="form-control" rows="4" placeholder="Write your review here"></textarea>

                        <div class="review-upload">
                            <label for="review-image" class="form-label">Attach an image to your review</label>
                            <input type="file" id="review-image" accept="image/*" class="form-control">
                        </div>

                        <button class="btn btn-accent mt-3" id="submit">Submit</button>
                        <div class="reviews" id="reviews"></div>
                    </div>
                </section>
            </div>
        </main>
    <?php endif; ?>

    <script src="Scripts/product-gallery.js"></script>
    <script src="Scripts/product-quantity.js"></script>
    <script src="Scripts/product-review.js"></script>
    <script src="Scripts/add-to-cart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>