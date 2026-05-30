<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/products-navbar.css">
    <link rel="stylesheet" href="CSS/product_list.css">
</head>

<body>
    <?php include 'products-navbar.php';
    require_once __DIR__ . '/data/products.php';

    // Parse search input (CSV-aware) into terms for display
    $searchInput = $_POST['search'] ?? '';
    $searchTerms = [];
    if (trim($searchInput) !== '') {
        $parsed = str_getcsv($searchInput);
        $parsed = array_map('trim', $parsed);
        $searchTerms = array_values(array_filter($parsed, function ($t) {
            return $t !== '';
        }));
    }
    ?>

    <div class="top-bar">
        <marquee>
            <h4>S T R E A M &nbsp; S O L E A N A ! &nbsp; A L B U M &nbsp; C O M I N G &nbsp; S O O N ! </h4>
        </marquee>
    </div>

    <main class="page-shell container-fluid py-4">
        <div class="row g-4">
            <aside class="col-12 col-lg-3 col-xl-2">
                <div class="sidebar-panel p-3">
                    <div class="sidebar-title d-flex align-items-baseline justify-content-between">
                        <h2 class="h5 mb-0">Items</h2>
                        <span class="item-count">(10)</span>
                    </div>
                    <hr class="sidebar-divider">
                    <ul class="category-list list-unstyled mb-0">
                        <li>Guitars<sup>(3)</sup></li>
                        <li>Keyboards<sup>(3)</sup></li>
                        <li>Bass Guitars<sup>(2)</sup></li>
                        <li>Pedals<sup>(2)</sup></li>
                        <li><del>Drums</del><sup>(Soon)</sup></li>
                    </ul>

                    <form action="product_list.php" method="post" class="search-form mt-4">
                        <label for="search-input" class="form-label text-uppercase small mb-2">Search Here</label>
                        <div class="input-group">
                            <input id="search-input" type="text" name="search" class="form-control" placeholder="Keyboards, pedals, Fender...">
                            <button class="btn btn-accent" type="submit">Search</button>
                        </div>
                        <div class="form-text">Use commas to search multiple terms.</div>
                    </form>
                </div>
            </aside>

            <section class="col-12 col-lg-9 col-xl-10">
                <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-3">
                    <h1 class="page-title mb-0">Products</h1>
                    <?php if (!empty($searchTerms)): ?>
                        <div class="search-terms">
                            <span class="search-terms__label">Searched for:</span>
                            <?php foreach ($searchTerms as $term): ?>
                                <span class="search-term"><?= htmlspecialchars($term) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="row g-4">
                    <?php foreach ($products as $productKey => $product): ?>
                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="product-card h-100">
                                <a href="product.php?key=<?= htmlspecialchars($productKey) ?>" class="product-card__image">
                                    <img src="<?= htmlspecialchars($product->imagesByView['img1']) ?>" alt="<?= htmlspecialchars($product->title) ?>" class="img-fluid">
                                </a>
                                <div class="product-card__body">
                                    <h3 class="product-card__title mb-1"><?= htmlspecialchars($product->title) ?></h3>
                                    <p class="product-card__price mb-0">$<?= htmlspecialchars($product->price) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>