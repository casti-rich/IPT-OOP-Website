<?php
session_start();
require_once __DIR__ . '/database/db.php';
processExpiredRentals($conn);

function loadRentalProducts(mysqli $conn, string $filter): array
{
    $where = "WHERE p.Product_Status = 'For Rent'";

    if ($filter === 'studio') {
        $where .= " AND p.Product_Category = 'Studio'";
    } elseif ($filter === 'equipment') {
        $where .= " AND p.Product_Category != 'Studio'";
    }

    $sql = "SELECT p.Product_ID, p.Product_Name, p.Product_Desc, p.Product_Category, p.Product_Price, p.Product_Image_Path, COALESCE(pi.Stock, 0) AS Stock
            FROM products p
            LEFT JOIN product_inventory pi ON p.Product_ID = pi.Product_ID
            " . $where . "
            ORDER BY p.Product_Name";

    $products = [];
    $res = mysqli_query($conn, $sql);

    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $product = (object) [
                'id' => (int) $row['Product_ID'],
                'title' => $row['Product_Name'],
                'description' => $row['Product_Desc'],
                'category' => $row['Product_Category'],
                'price' => (float) $row['Product_Price'],
                'inventory' => (int) $row['Stock'],
                'imagesByView' => [],
            ];

            if (!empty($row['Product_Image_Path'])) {
                $product->imagesByView['img1'] = $row['Product_Image_Path'];
            }

            $products[] = $product;
        }

        mysqli_free_result($res);
    }

    return $products;
}
// Queries Products available for Rent
function loadRentalProduct(mysqli $conn, int $productId): ?object
{
    if ($productId <= 0) {
        return null;
    }

    $stmt = mysqli_prepare(
        $conn,
        "SELECT p.Product_ID, p.Product_Name, p.Product_Desc, p.Product_Category, p.Product_Price, p.Product_Image_Path, COALESCE(pi.Stock, 0) AS Stock
         FROM products p
         LEFT JOIN product_inventory pi ON p.Product_ID = pi.Product_ID
         WHERE p.Product_ID = ? AND p.Product_Status = 'For Rent'
         LIMIT 1"
    );

    if (! $stmt) {
        return null;
    }

    mysqli_stmt_bind_param($stmt, 'i', $productId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = $res ? mysqli_fetch_assoc($res) : null;
    mysqli_stmt_close($stmt);

    if (! $row) {
        return null;
    }

    $product = (object) [
        'id' => (int) $row['Product_ID'],
        'title' => $row['Product_Name'],
        'description' => $row['Product_Desc'],
        'category' => $row['Product_Category'],
        'price' => (float) $row['Product_Price'],
        'inventory' => (int) $row['Stock'],
        'imagesByView' => [],
    ];

    if (!empty($row['Product_Image_Path'])) {
        $product->imagesByView['img1'] = $row['Product_Image_Path'];
    }

    return $product;
}

function getRentalUnit(object $product): string
{
    return $product->category === 'Studio' ? 'hour' : 'day';
}
// Set Rental Rate
function getRentalPrice(float $sellingPrice): float
{
    return $sellingPrice / 24;
}

// Queries Transactions with error handling function
function redirectWithMessage(int $productId, string $message): void
{
    $_SESSION['rent_message'] = $message;
    $target = $productId > 0 ? 'rent.php?id=' . $productId : 'rent.php';
    header('Location: ' . $target);
    exit();
}

if (! $conn) {
    die('Database connection error');
}

$rentMessage = $_SESSION['rent_message'] ?? '';
if ($rentMessage !== '') {
    unset($_SESSION['rent_message']);
}

$filter = $_GET['filter'] ?? '';
$selectedProductId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['rent_action'] ?? '') === 'rent_now') {
    $userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
    if ($userId <= 0) {
        header('Location: login.php');
        exit();
    }

    $productId = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
    $duration = isset($_POST['rental_duration']) ? (int) $_POST['rental_duration'] : 0;
    $paymentMethod = strtolower(trim((string) ($_POST['payment_method'] ?? 'cash')));

    if ($productId <= 0 || $duration <= 0) {
        redirectWithMessage($productId, 'Please choose a rental item and duration.');
    }

    if ($paymentMethod !== 'cash' && $paymentMethod !== 'gcash') {
        redirectWithMessage($productId, 'Unsupported payment method selected.');
    }


    $stmt = mysqli_prepare(
    $conn,
    "SELECT COUNT(*) AS active_count
     FROM rentals r
     INNER JOIN transactions t
        ON t.Transaction_ID = r.Transaction_ID
     WHERE t.User_ID = ?
       AND r.Status = 'active'
       AND r.Rent_End > NOW()");

    if (! $stmt) {
        redirectWithMessage($productId, 'Unable to verify active rentals.');
    }
    
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    $activeRental = mysqli_fetch_assoc($result);
    
    mysqli_stmt_close($stmt);
    
    if (($activeRental['active_count'] ?? 0) > 0) {
        redirectWithMessage( $productId,
            'You already have an active rental. You can rent another item once your current rental period ends.');
    }

    mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);

    try {
        $stmt = mysqli_prepare(
            $conn,
            "SELECT p.Product_ID, p.Product_Name, p.Product_Desc, p.Product_Category, p.Product_Price, p.Product_Image_Path, COALESCE(pi.Stock, 0) AS Stock
             FROM products p
             LEFT JOIN product_inventory pi ON p.Product_ID = pi.Product_ID
             WHERE p.Product_ID = ? AND p.Product_Status = 'For Rent'
             FOR UPDATE"
        );

        if (! $stmt) {
            throw new Exception('Prepare failed: ' . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, 'i', $productId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);

        if (! $row) {
            throw new Exception('Rental item not found.');
        }

        $stock = (int) $row['Stock'];
        if ($stock <= 0) {
            throw new Exception('This rental item is currently out of stock.');
        }

        $baseRate = getRentalPrice((float)$row['Product_Price']);
        $totalAmount = $baseRate * $duration;
        $durationUnit = $row['Product_Category'] === 'Studio' ? 'hour' : 'day';
        $rentStart = date('Y-m-d H:i:s');
        $rentEnd = date('Y-m-d H:i:s', strtotime('+' . $duration . ' ' . $durationUnit));

        $stmt = mysqli_prepare($conn, 'INSERT INTO transactions (User_ID, Transaction_Type, Amount, Created_At) VALUES (?, ?, ?, NOW())');
        if (! $stmt) {
            throw new Exception('Prepare failed: ' . mysqli_error($conn));
        }

        $transactionType = 'Rental';
        mysqli_stmt_bind_param($stmt, 'isd', $userId, $transactionType, $totalAmount);
        if (! mysqli_stmt_execute($stmt)) {
            throw new Exception('Failed to create transaction: ' . mysqli_stmt_error($stmt));
        }

        $transactionId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        $stmt = mysqli_prepare(
            $conn,
            'INSERT INTO rentals (Transaction_ID, Product_ID, Rent_Start, Rent_End, Actual_Return, Status) VALUES (?, ?, ?, ?, NULL, ?)'
        );
        if (! $stmt) {
            throw new Exception('Prepare failed: ' . mysqli_error($conn));
        }

        $rentalStatus = 'active';
        mysqli_stmt_bind_param($stmt, 'iisss', $transactionId, $productId, $rentStart, $rentEnd, $rentalStatus);
        if (! mysqli_stmt_execute($stmt)) {
            throw new Exception('Failed to create rental: ' . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);

        $stmt = mysqli_prepare($conn, 'UPDATE product_inventory SET Stock = Stock - 1 WHERE Product_ID = ?');
        if (! $stmt) {
            throw new Exception('Prepare failed: ' . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, 'i', $productId);
        if (! mysqli_stmt_execute($stmt)) {
            throw new Exception('Failed to update inventory: ' . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);

        if ($paymentMethod === 'gcash') {
            $gatewayRef = 'GCASH-RENT-DEMO-' . $transactionId;
            $stmt = mysqli_prepare(
                $conn,
                'INSERT INTO payments (Transaction_ID, Amount, Payment_Method, Gateway_ref, Payment_Date, Status) VALUES (?, ?, ?, ?, NOW(), ?)'
            );
            if (! $stmt) {
                throw new Exception('Prepare failed: ' . mysqli_error($conn));
            }

            $paymentStatus = 'completed';
            mysqli_stmt_bind_param($stmt, 'idsss', $transactionId, $totalAmount, $paymentMethod, $gatewayRef, $paymentStatus);
        } else {
            $stmt = mysqli_prepare(
                $conn,
                'INSERT INTO payments (Transaction_ID, Amount, Payment_Method, Gateway_ref, Payment_Date, Status) VALUES (?, ?, ?, NULL, NOW(), ?)'
            );
            if (! $stmt) {
                throw new Exception('Prepare failed: ' . mysqli_error($conn));
            }

            $paymentStatus = 'completed';
            mysqli_stmt_bind_param($stmt, 'idss', $transactionId, $totalAmount, $paymentMethod, $paymentStatus);
        }

        if (! mysqli_stmt_execute($stmt)) {
            throw new Exception('Failed to create payment: ' . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);
        mysqli_commit($conn);

        redirectWithMessage($productId, 'Rental confirmed! Your booking has been created successfully.');
    } catch (Exception $e) {
        mysqli_rollback($conn);
        redirectWithMessage($productId, 'Rental failed: ' . $e->getMessage());
    }
}

$products = loadRentalProducts($conn, $filter);
$selectedProduct = $selectedProductId > 0 ? loadRentalProduct($conn, $selectedProductId) : null;
$selectedUnit = $selectedProduct ? getRentalUnit($selectedProduct) : 'day';
$selectedMaxDuration = $selectedUnit === 'hour' ? 24 : 30;
$hasActiveRental = false;

if (isset($_SESSION['user_id'])) {
    $accountUserId = (int) $_SESSION['user_id'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT 1
         FROM rentals r
         INNER JOIN transactions t
            ON t.Transaction_ID = r.Transaction_ID
         WHERE t.User_ID = ?
           AND r.Status = 'active'
           AND r.Rent_End > NOW()
         LIMIT 1"
    );

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $accountUserId);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $hasActiveRental = mysqli_num_rows($result) > 0;

        mysqli_stmt_close($stmt);
    }
}

if ($selectedProduct && $selectedProduct->inventory <= 0 && $rentMessage === '') {
    $rentMessage = 'This item is out of stock.';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($selectedProduct ? $selectedProduct->title . ' Rental' : 'Rentals') ?></title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISvWaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/products-navbar.css">
    <link rel="stylesheet" href="CSS/product_list.css">
</head>

<body>
    <?php include 'products-navbar.php'; ?>

    <div class="top-bar">
        <marquee>
            <h4>S T R E A M &nbsp; S O L E A N A ! &nbsp; A L B U M &nbsp; C O M I N G &nbsp; S O O N ! </h4>
        </marquee>
    </div>

    <main class="page-shell container-fluid py-4">
        <?php if ($rentMessage !== ''): ?>
            <div class="alert alert-info mb-4" role="status" aria-live="polite">
                <?= htmlspecialchars($rentMessage) ?>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <aside class="col-12 col-lg-3 col-xl-2">
                <div class="sidebar-panel p-3">
                    <div class="sidebar-title d-flex align-items-baseline justify-content-between">
                        <h2 class="h5 mb-0">Rental Items</h2>
                        <span class="item-count">(<?= htmlspecialchars((string) count($products)) ?>)</span>
                    </div>
                    <hr class="sidebar-divider">
                    <ul class="category-list list-unstyled mb-0">
                        <li><a href="rent.php?filter=equipment">Equipments</a></li>
                        <li><a href="rent.php?filter=studio">Studios</a></li>
                        <li><a href="rent.php">All Rentals</a></li>
                    </ul>
                </div>
            </aside>

            <section class="col-12 col-lg-9 col-xl-10">
                <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-3">
                    <h1 class="page-title mb-0"><?= $selectedProduct ? htmlspecialchars($selectedProduct->title) : 'Rentals' ?></h1>
                    <?php if ($selectedProduct): ?>
                        <a href="rent.php<?= $filter !== '' ? '?filter=' . htmlspecialchars($filter) : '' ?>" class="btn btn-outline-secondary">Back to rentals</a>
                    <?php endif; ?>
                </div>

                <?php if ($selectedProduct): ?>
                    <?php
                    $selectedImage = $selectedProduct->imagesByView['img1'] ?? 'Assets/Products/Placehold/01.jpg';
                    $rateLabel = $selectedUnit === 'hour' ? 'hour' : 'day';
                    $durationLabel = $selectedUnit === 'hour' ? 'hour' : 'day';
                    ?>
                    <div class="row g-4 align-items-start">
                        <div class="col-12 col-lg-6">
                            <div class="product-card h-100">
                                <div class="product-card__image">
                                    <img src="<?= htmlspecialchars($selectedImage) ?>" alt="<?= htmlspecialchars($selectedProduct->title) ?>" class="img-fluid">
                                </div>
                                <div class="product-card__body">
                                    <h3 class="product-card__title mb-2"><?= htmlspecialchars($selectedProduct->title) ?></h3>
                                    <p class="mb-2"><?= htmlspecialchars($selectedProduct->description) ?></p>
                                    <p class="product-card__price mb-1">$<?= htmlspecialchars(number_format(getRentalPrice($selectedProduct->price), 2)) ?> / <?= htmlspecialchars($rateLabel) ?></p>
                                    <p class="mb-0">Stock: <?= htmlspecialchars((string) $selectedProduct->inventory) ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4 p-lg-5">
                                    <form method="post" action="rent.php<?= $selectedProductId > 0 ? '?id=' . htmlspecialchars((string) $selectedProductId) . ($filter !== '' ? '&filter=' . htmlspecialchars($filter) : '') : '' ?>" class="d-grid gap-4" id="rent-form">
                                        <input type="hidden" name="rent_action" value="rent_now">
                                        <input type="hidden" name="product_id" value="<?= htmlspecialchars((string) $selectedProduct->id) ?>">

                                        <div>
                                            <p class="text-uppercase text-secondary mb-1">Rental details</p>
                                            <h2 class="h3 mb-3">Reserve this item directly</h2>
                                            <p class="mb-0">Choose how long you want to rent, review the rate, then complete payment.</p>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <label for="rental-duration" class="form-label">Rental Duration</label>
                                                <select
                                                    id="rental-duration"
                                                    name="rental_duration"
                                                    class="form-select"
                                                    data-rate="<?= htmlspecialchars(number_format(getRentalPrice($selectedProduct->price), 2, '.', '')) ?>"
                                                    data-unit="<?= htmlspecialchars($selectedUnit) ?>"
                                                    required
                                                >
                                                    <?php for ($duration = 1; $duration <= $selectedMaxDuration; $duration++): ?>
                                                        <option value="<?= htmlspecialchars((string) $duration) ?>"><?= htmlspecialchars((string) $duration) ?> <?= htmlspecialchars($duration === 1 ? $durationLabel : $durationLabel . 's') ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label class="form-label">Base Rate</label>
                                                <div class="form-control-plaintext fw-semibold">$<?= htmlspecialchars(number_format(getRentalPrice($selectedProduct->price), 2)) ?> / <?= htmlspecialchars($rateLabel) ?></div>
                                            </div>
                                        </div>

                                        <div class="panel-card summary-card">
                                            <div class="summary-row">
                                                <span>Duration</span>
                                                <span id="rental-duration-label"><?= htmlspecialchars('1 ' . $durationLabel) ?></span>
                                            </div>
                                            <div class="summary-row">
                                                <span>Rate</span>
                                                <span>$<?= htmlspecialchars(number_format(getRentalPrice($selectedProduct->price), 2)) ?> / <?= htmlspecialchars($rateLabel) ?></span>
                                            </div>
                                            <div class="summary-row">
                                                <span>Total</span>
                                                <span id="rental-total">$<?= htmlspecialchars(number_format(getRentalPrice($selectedProduct->price), 2)) ?></span>
                                            </div>
                                        </div>

                                        <div class="panel-card summary-card">
                                            <div class="summary-row mb-2">
                                                <span>Payment Method</span>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="payment_method" id="payment-cash" value="cash" checked>
                                                <label class="form-check-label" for="payment-cash">Cash on Pickup</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_method" id="payment-gcash" value="gcash">
                                                <label class="form-check-label" for="payment-gcash">GCash</label>
                                            </div>
                                        </div>

                                        <?php if ($hasActiveRental): ?>
                                            <button class="btn btn-secondary btn-lg" disabled>
                                                You already have an active rental
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                Rent Out Now
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-3">
                        <p class="mb-0 text-secondary">Select a rental item to view its rate, duration options, and payment method.</p>
                    </div>

                    <div class="row g-4">
                        <?php foreach ($products as $product): ?>
                            <?php
                            $detailHref = 'rent.php?id=' . $product->id;
                            if ($filter !== '') {
                                $detailHref .= '&filter=' . urlencode($filter);
                            }
                            $imgSrc = $product->imagesByView['img1'] ?? 'Assets/Products/Placehold/01.jpg';
                            $unit = getRentalUnit($product);
                            ?>
                            <div class="col-6 col-md-4 col-xl-3">
                                <div class="product-card h-100">
                                    <a href="<?= htmlspecialchars($detailHref) ?>" class="product-card__image">
                                        <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($product->title) ?>" class="img-fluid">
                                    </a>
                                    <div class="product-card__body">
                                        <h3 class="product-card__title mb-1"><?= htmlspecialchars($product->title) ?></h3>
                                        <p class="product-card__price mb-1">$<?= htmlspecialchars(number_format(getRentalPrice($product->price), 2)) ?> / <?= htmlspecialchars($unit) ?></p>
                                        <p class="mb-0 text-secondary">Stock: <?= htmlspecialchars((string) $product->inventory) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <script>
        (function () {
            const durationSelect = document.getElementById('rental-duration');
            const durationLabel = document.getElementById('rental-duration-label');
            const totalLabel = document.getElementById('rental-total');

            if (!durationSelect || !durationLabel || !totalLabel) {
                return;
            }

            const rate = Number.parseFloat(durationSelect.dataset.rate || '0');
            const unit = durationSelect.dataset.unit === 'hour' ? 'hour' : 'day';

            const updateTotals = () => {
                const duration = Number.parseInt(durationSelect.value, 10) || 1;
                const total = rate * duration;
                durationLabel.textContent = `${duration} ${duration === 1 ? unit : `${unit}s`}`;
                totalLabel.textContent = `$${total.toFixed(2)}`;
            };

            durationSelect.addEventListener('change', updateTotals);
            updateTotals();
        }());
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>