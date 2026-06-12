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
    <?php
    include 'products-navbar.php';
    require_once __DIR__ . '/database/db.php';

    // Parse search input into terms for display
    $searchInput = $_POST['search'] ?? '';
    $searchTerms = [];
    if (trim($searchInput) !== '') {
        $parsed = str_getcsv($searchInput);
        $parsed = array_map('trim', $parsed);
        $searchTerms = array_values(array_filter($parsed, function ($t) {
            return $t !== '';
        }));
    }

    // Build product list from DB
    $products = [];
    // Filters products
    $filter = $_GET['filter'] ?? '';
    $where = "WHERE p.Product_Status = 'On Sale'";
    if ($filter === 'guitar') {
    $where .= " AND p.Product_Category = 'Guitar'";
    }
    elseif ($filter === 'keyboard') {
        $where .= " AND p.Product_Category = 'Keyboard'";
    }
    elseif ($filter === 'bass') {
        $where .= " AND p.Product_Category = 'Bass Guitar'";
    }
    elseif ($filter === 'pedal') {
        $where .= " AND p.Product_Category = 'Pedal'";
    }

    if (! empty($searchTerms)) {
        $clauses = [];
        foreach ($searchTerms as $term) {
            $t = mysqli_real_escape_string($conn, $term);
            $clauses[] = "(p.Product_Name LIKE '%" . $t . "%' OR p.Product_Desc LIKE '%" . $t . "%')";
        }
        $where .= ' AND ' . implode(' OR ', $clauses);
    }

    $sql = "SELECT p.Product_ID, p.Product_Name, p.Product_Desc, p.Product_Category, p.Product_Price, p.Product_Image_Path, COALESCE(pi.Stock,0) AS Stock FROM products p LEFT JOIN product_inventory pi ON p.Product_ID = pi.Product_ID " . $where . " ORDER BY p.Product_Name";
    $res = mysqli_query($conn, $sql);
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $prod = (object) [
                'id' => (int)$row['Product_ID'],
                'title' => $row['Product_Name'],
                'description' => $row['Product_Desc'],
                'category' => $row['Product_Category'],
                'price' => (float)$row['Product_Price'],
                'inventory' => (int)$row['Stock'],
                'imagesByView' => [],
            ];
            if (! empty($row['Product_Image_Path'])) $prod->imagesByView['img1'] = $row['Product_Image_Path'];
            $products[] = $prod;
        }
        mysqli_free_result($res);
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
                        <span class="item-count">(<?= htmlspecialchars((string) count($products)) ?>)</span>
                    </div>
                    <hr class="sidebar-divider">
                    <ul class="category-list list-unstyled mb-0">
                        <li><a href="product_list.php">All Items</a></li>

                        <li><a href="product_list.php?filter=bass">Bass</a></li>

                        <li><a href="product_list.php?filter=guitar">Guitar</a></li>

                        <li><a href="product_list.php?filter=keyboard">Keyboard</a></li>

                        <li><a href="product_list.php?filter=pedal">Pedal</a></li>

                    </ul>

                    <form id="search-form" action="product_list.php" method="post" class="search-form mt-4">
                        <label for="search-input" class="form-label text-uppercase small mb-2">Search Here</label>
                        <div class="input-group">
                            <input id="search-input" type="text" name="search" class="form-control" placeholder="Keyboards, pedals, Fender..." value="<?= htmlspecialchars($searchInput) ?>">
                            <button type="button" class="btn btn-outline-secondary btn-sm search-clear" onclick="clearSearch()" aria-label="Clear search" title="Clear search">&times;</button>
                            <button class="btn btn-accent search-submit" type="submit">Search</button>
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
                    <?php if (count($products) === 0): ?>
                        <div class="col-12">
                            <div class="empty-state p-4 text-center">
                                <h3>No products found</h3>
                                <p>Try different keywords or clear the search.</p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($products as $product): ?>
                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="product-card h-100">
                                <a href="product.php?id=<?= htmlspecialchars((string) $product->id) ?>" class="product-card__image">
                                    <?php $imgSrc = $product->imagesByView['img1'] ?? 'Assets/Products/Placehold/01.jpg'; ?>
                                    <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($product->title) ?>" class="img-fluid">
                                </a>
                                <div class="product-card__body">
                                    <h3 class="product-card__title mb-1"><?= htmlspecialchars($product->title) ?></h3>
                                    <p class="product-card__price mb-0">$<?= htmlspecialchars((string) $product->price) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        function clearSearch() {
            var input = document.getElementById('search-input');
            if (input) input.value = '';
            var form = document.getElementById('search-form');
            if (form) form.submit();
        }
    </script>
</body>
</html>