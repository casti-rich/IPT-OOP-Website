<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="CSS/products-navbar.css">
    <link rel="stylesheet" href="CSS/product_list.css">
    <style>
        li {
            margin-left: 1em;
            margin-top: 1em;
            font-size: 20px;
        }

        figcaption {
            font-size: 13px;
            margin-top: 0em;
        }

        marquee {
            margin-top: 0.5em;
        }

        img {
            margin-top: -0.8em;
        }

        .search-terms {
            margin: 12px 0 18px;
            font-size: 18px;
            color: #fff;
            margin-top: -30.3em;
            margin-right: 51em;
        }

        .search-term {
            display: inline-block;
            background: rgba(255, 255, 255, 0.12);
            padding: 6px 10px;
            margin-right: 8px;
            border-radius: 999px;
            font-weight: 700;
        
        }
    </style>
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
        <Marquee>
            <h4>S T R E A M &nbsp; S O L E A N A ! &nbsp; A L B U M &nbsp; C O M I N G &nbsp; S O O N ! </h4>
        </marquee>
    </div>
    <div class="megacontainer">
        <div class="navi">
            <h2>ITEMS<sup>(10)</sup>
                <hr>
            </h2>
            <ul style="list-style-type: square">
                <li>Guitars<sup>(3)</sup><br></li>
                <li>Keyboards<sup>(3)</sup><br></li>
                <li>Bass Guitars<sup>(2)</sup></li>
                <li>Pedals<sup>(2)</sup></li>
                <li><del>Drums</del><sup>(Soon)</sup></li>
            </ul><br>
            <form action="product_list.php" method="post">
                <p>Search Here</p>
                <input type="text" name="search" size="20"><br>
                <input type="submit" value="Search">
            </form>
        </div>
        <div class="container">
            <?php $index = 1; ?>
            <?php foreach ($products as $productKey => $product): ?>
                <div class="item item <?= htmlspecialchars((string) $index) ?>">
                    <figure>
                        <a href="product.php?key=<?= htmlspecialchars($productKey) ?>">
                            <img src="<?= htmlspecialchars($product->imagesByView['img1']) ?>" alt="<?= htmlspecialchars($product->title) ?>" height="150" width="150" style="margin-left:-0.85em">
                        </a>
                        <figcaption><strong><?= htmlspecialchars($product->title) ?></strong>
                            <p>$<?= htmlspecialchars($product->price) ?></p>
                        </figcaption>
                    </figure>
                </div>
                <?php $index++; ?>
            <?php endforeach; ?>
            <?php if (!empty($searchTerms)): ?>
                <div class="search-terms">Searched for:
                    <?php foreach ($searchTerms as $term): ?>
                        <span class="search-term"><?= htmlspecialchars($term) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
</body>

</html>