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
        }

        .search-term {
            display: inline-block;
            background: rgba(255,255,255,0.12);
            padding: 6px 10px;
            margin-right: 8px;
            border-radius: 999px;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <?php include 'products-navbar.php';
    spl_autoload_register(function ($class) {
        $classFile = __DIR__ . '/Classes/' . $class . '.inc.php';

        if (file_exists($classFile)) {
            require_once $classFile;
        }
    });
    $basePath = 'Assets/Products/Placehold/';

    $prod1 = new Product(
        1,
        "Fender Rhodes Keyboard",
        "a",
        990.00,
        5,
        true,
        [
            "img1" => $basePath . 'rhodes.jpg',
        ]
    );

    $prod2 = new Product(
        1,
        "Gibson Les Paul Jr. Ikuyo Kita Model",
        "a",
        458.00,
        5,
        true,
        [
            "img1" => $basePath . 'kita.jpg',
        ]
    );

    $prod3 = new Product(
        1,
        "Rickenbacker 4003 Bass",
        "a",
        670.00,
        5,
        true,
        [
            "img1" => $basePath . '01.jpg',
        ]
    );

    $prod4 = new Product(
        1,
        "Fender Precision Plus Bass",
        "a",
        550.00,
        5,
        true,
        [
            "img1" => $basePath . '02.jpg',
        ]
    );

    $prod5 = new Product(
        1,
        "1959 Gibson Les Paul Standard",
        "a",
        750000.00,
        1,
        true,
        [
            "img1" => $basePath . '03.jpg',
        ]
    );

    $prod6 = new Product(
        1,
        "Vox Continental Keyboard",
        "a",
        15000.00,
        5,
        true,
        [
            "img1" => $basePath . '04.jpg',
        ]
    );

    $prod7 = new Product(
        1,
        "Casio CTK-7200 Keyboard",
        "a",
        950.00,
        5,
        true,
        [
            "img1" => $basePath . 'casio.jpg',
        ]
    );

    $prod8 = new Product(
        1,
        "M-Vave ANN Black Box",
        "a",
        50.00,
        5,
        true,
        [
            "img1" => $basePath . '06.jpg',
        ]
    );

    $prod9 = new Product(
        1,
        "Ibanez Ts9 Overdrive Pedal",
        "a",
        100.00,
        5,
        true,
        [
            "img1" => $basePath . '07.jpg',
        ]
    );

    $prod10 = new Product(
        1,
        "Vintage Fender Stratocaster",
        "a",
        595000.00,
        5,
        true,
        [
            "img1" => $basePath . '08.jpg',
        ]
    );

    // Parse search input (CSV-aware) into terms for display
    $searchInput = $_POST['search'] ?? '';
    $searchTerms = [];
    if (trim($searchInput) !== '') {
        $parsed = str_getcsv($searchInput);
        $parsed = array_map('trim', $parsed);
        $searchTerms = array_values(array_filter($parsed, function($t){ return $t !== ''; }));
    }
    ?>

    <div class="top-bar">
        <Marquee>
            <h4>S T R E A M &nbsp; S O L E A N A ! &nbsp; A L B U M &nbsp; C O M I N G &nbsp; S O O N ! </h4>
        </marquee>
    </div>
    <?php if (!empty($searchTerms)): ?>
        <div class="search-terms">Searched for:
            <?php foreach ($searchTerms as $term): ?>
                <span class="search-term"><?= htmlspecialchars($term) ?></span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="megacontainer">
        <div class="navi">
            <h2>ITEMS<sup>(15)</sup>
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
            <div class="item item 1">
                <figure>
                    <img src="<?= htmlspecialchars($prod1->imagesByView['img1']) ?>" alt="item" height="150" width="150" usemap="#item" style="margin-left:-0.85em">
                    <figcaption><strong><?= htmlspecialchars($prod1->title) ?></strong>
                        <p>$<?= htmlspecialchars($prod1->price) ?></p>
                    </figcaption>
                </figure>
                <map name="item">
                    <area shape="rect" coords="0,0,3000,3000" href="Fender_Rhodes.php">
            </div>
            <div class="item item 2">
                <figure>
                    <img src="<?= htmlspecialchars($prod2->imagesByView['img1']) ?>" alt="item" height="150" width="150" usemap="#item2" style="margin-left:-0.85em;">
                    <figcaption><strong><?= htmlspecialchars($prod2->title) ?></strong>
                        <p>$<?= htmlspecialchars($prod2->price) ?></p>
                    </figcaption>
                </figure>
                <map name="item2">
                    <area shape="rect" coords="0,0,3000,3000" href="LesPaul-Jr._KitaModel.php">
            </div>
            <div class="item item 3">
                <figure>
                    <img src="<?= htmlspecialchars($prod3->imagesByView['img1']) ?>" alt="item" height="150" width="150" style="margin-left:-0.85em">
                    <figcaption><strong><?= htmlspecialchars($prod3->title) ?></strong>
                        <p>$<?= htmlspecialchars($prod3->price) ?></p>
                    </figcaption>
                </figure>
            </div>
            <div class="item item 4">
                <figure>
                    <img src="<?= htmlspecialchars($prod4->imagesByView['img1']) ?>" alt="item" height="150" width="150" style="margin-left:-0.85em">
                    <figcaption><strong><?= htmlspecialchars($prod4->title) ?></strong>
                        <p>$<?= htmlspecialchars($prod4->price) ?></p>
                    </figcaption>
                </figure>
            </div>
            <div class="item item 5">
                <figure>
                    <img src="<?= htmlspecialchars($prod5->imagesByView['img1']) ?>" alt="item" height="150" width="150" style="margin-left:-0.85em">
                    <figcaption><strong><?= htmlspecialchars($prod5->title) ?></strong>
                        <p>$<?= htmlspecialchars($prod5->price) ?></p>
                    </figcaption>
                </figure>
            </div>
            <div class="item item 6">
                <figure>
                    <img src="<?= htmlspecialchars($prod6->imagesByView['img1']) ?>" alt="item" height="150" width="150" style="margin-left:-0.85em">
                    <figcaption><strong><?= htmlspecialchars($prod6->title) ?></strong>
                        <p>$<?= htmlspecialchars($prod6->price) ?></p>
                    </figcaption>
                </figure>
            </div>
            <div class="item item 7">
                <figure>
                    <img src="<?= htmlspecialchars($prod7->imagesByView['img1']) ?>" alt="item" height="150" width="150" style="margin-left:-0.85em">
                    <figcaption><strong><?= htmlspecialchars($prod7->title) ?></strong>
                        <p>$<?= htmlspecialchars($prod7->price) ?></p>
                    </figcaption>
                </figure>
            </div>
            <div class="item item 8">
                <figure>
                    <img src="<?= htmlspecialchars($prod8->imagesByView['img1']) ?>" alt="item" height="150" width="150" style="margin-left:-0.85em">
                    <figcaption><strong><?= htmlspecialchars($prod8->title) ?></strong>
                        <p>$<?= htmlspecialchars($prod8->price) ?></p>
                    </figcaption>
                </figure>
            </div>
            <div class="item item 9">
                <figure>
                    <img src="<?= htmlspecialchars($prod9->imagesByView['img1']) ?>" alt="item" height="150" width="150" style="margin-left:-0.85em">
                    <figcaption><strong><?= htmlspecialchars($prod9->title) ?></strong>
                        <p>$<?= htmlspecialchars($prod9->price) ?></p>
                    </figcaption>
                </figure>
            </div>
            <div class="item item 10">
                <figure>
                    <img src="<?= htmlspecialchars($prod10->imagesByView['img1']) ?>" alt="item" height="150" width="150" style="margin-left:-0.85em">
                    <figcaption><strong><?= htmlspecialchars($prod10->title) ?></strong>
                        <p>$<?= htmlspecialchars($prod10->price) ?></p>
                    </figcaption>
                </figure>
            </div>
</body>

</html>