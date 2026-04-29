<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Viewing Page</title>
    <link rel="stylesheet" href="CSS/product-viewing-page.css">
</head>
<body>
    <?php 
    spl_autoload_register(function($class) {
        $classFile = __DIR__ . '/Classes/' . $class . '.inc.php';

        if (file_exists($classFile)) {
            require_once $classFile;
        }
    });
    $basePath = 'Assets/Products/Fender_Rhodes/';
    
    $prod1 = new Keyboard(
        1,
        "Fender Rhodes Suitcase 73-Key",
        "The Fender Rhodes Suitcase is a classic electric piano that has been a staple in the music industry for decades.",
        458.00,
        3,
        true,
        [
            "img1" => $basePath . '01.jpg',
            "img2" => $basePath . '02.jpg',
            "img3" => $basePath . '03.jpg',
            "img4" => $basePath . '04.jpg',
            "img5" => $basePath . '05.jpg',
        ],
        73
    );      
    ?>
    
    <div class="top-bar"></div>

    <main class="page-wrap" aria-label="Product viewing layout">
        <section class="product-preview" aria-label="Product image preview">
            <div class="preview-large"><img src="<?= htmlspecialchars($prod1->imagesByView['img1']) ?>" alt="Product image 1" class="main_thumb"></div>
            <div class="preview-thumbs">
                <div class="thumb"><img src="<?= htmlspecialchars($prod1->imagesByView['img2']) ?>" alt="Product image 2" class="sub_thumb"></div>
                <div class="thumb"><img src="<?= htmlspecialchars($prod1->imagesByView['img3']) ?>" alt="Product image 3" class="sub_thumb"></div>
                <div class="thumb"><img src="<?= htmlspecialchars($prod1->imagesByView['img4']) ?>" alt="Product image 4" class="sub_thumb"></div>
                <div class="thumb"><img src="<?= htmlspecialchars($prod1->imagesByView['img5']) ?>" alt="Product image 5" class="sub_thumb"></div>
            </div>
        </section>

        <section class="product-details" aria-label="Product information">
            <h1 class="product-title"><?= htmlspecialchars($prod1->title) ?></h1>
            <p class="product-description">
                <?= htmlspecialchars($prod1->description) ?>
            </p>
            <p class="product-description">
                Type: <?= htmlspecialchars($prod1->numberOfKeys) ?>-Key Electric Piano
            </p>
            <p class="product-price">$ <?= htmlspecialchars($prod1->price) ?></p>
            <p class="product-inventory">Stocks: <?= htmlspecialchars($prod1->inventory) ?></p>

            <div class="actions-row" aria-label="Quantity and add to cart">
                <button class="icon-btn" aria-label="Increase quantity">+</button>
                <span class="qty-value">0</span>
                <button class="icon-btn" aria-label="Decrease quantity">-</button>
                <button class="cta-btn">Add to cart</button>
            </div>
        </section>
    </main>

    <script src="Scripts/product-quantity.js"></script>
</body>
</html>