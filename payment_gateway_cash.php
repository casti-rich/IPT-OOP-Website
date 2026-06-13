<?php
session_start();

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    $_SESSION['checkout_message'] = 'Your cart is empty.';
    header('Location: check-out-page.php');
    exit();
}

$subtotal = 0.0;
$itemLines = [];
foreach ($cart as $item) {
    $qty = isset($item['quantity']) ? (int) $item['quantity'] : 0;
    $price = isset($item['price']) ? (float) $item['price'] : 0.0;
    $title = $item['title'] ?? '';
    $subtotal += $qty * $price;
    $itemLines[] = ['title' => $title, 'qty' => $qty, 'price' => $price];
}

$shipping = 1.00;
$total = $subtotal + $shipping;
$prefillEmail = $_SESSION['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cash On Pickup</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/payment_gateway_cash.css">
    <link rel="stylesheet" href="CSS/products-navbar.css">
    <link rel="stylesheet" href="CSS/style.css">
</head>

<body class="gateway-theme">
    <?php include 'products-navbar.php'; ?>
    <main>
        <div class="container py-4">
            <div class="d-flex flex-row gap-5 mt-3">
                <h2 style="width: 400px;">Cash On Pickup</h2>
            </div>
            <div class="d-flex flex-row gap-5 flex-wrap">
                <div class="p-5 border border-secondary-subtle" style="width: 700px;" id="first-row">
                    <form aria-label="Cash on pickup details form" method="post" action="checkout.php" class="d-flex flex-column gap-3">
                        <input type="hidden" name="payment_method" value="cash">
                        <input type="hidden" name="payment_flow" value="confirm">

                        <div>
                            <p class="text-uppercase text-secondary mb-1">Pickup details</p>
                            <h2 class="mt-0 mb-3">Enter your personal details</h2>
                            <p class="mb-0">This is a pickup-only system, so please provide the details we need to complete the cash order.</p>
                        </div>

                        <div class="d-flex flex-row flex-wrap gap-3">
                            <div class="flex-fill">
                                <label for="first-name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first-name" name="first_name" placeholder="John" required>
                            </div>
                            <div class="flex-fill">
                                <label for="middle-name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middle-name" name="middle_name" placeholder="V.">
                            </div>
                            <div class="flex-fill">
                                <label for="last-name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last-name" name="last_name" placeholder="Doe" required>
                            </div>
                        </div>

                        <div class="d-flex flex-row flex-wrap gap-3">
                            <div class="flex-fill">
                                <label for="number" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="number" name="phone_number" placeholder="(123) 456-7890" required>
                            </div>
                            <div class="flex-fill">
                                <label for="email-address" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email-address" name="email" value="<?= htmlspecialchars($prefillEmail) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="<?= htmlspecialchars(number_format($total, 2, '.', '')) ?>" readonly>
                        </div>

                        <button type="submit" class="btn btn-primary">Confirm Cash Pickup</button>
                    </form>
                </div>

                <div class="p-5 border border-secondary-subtle" style="width: 450px;" id="second-row">
                    <h2 class="my-3">Summary of Orders</h2>
                    <div class="card" style="width: 20rem;">
                        <ul class="list-group list-group-flush p-2">
                            <?php if (empty($itemLines)): ?>
                                <li class="list-group-item">No items in cart</li>
                            <?php else: ?>
                                <?php foreach ($itemLines as $line): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold"><?= htmlspecialchars($line['title']) ?></div>
                                            <small class="text-muted">Qty: <?= htmlspecialchars((string) $line['qty']) ?></small>
                                        </div>
                                        <div>$<?= htmlspecialchars(number_format($line['price'] * $line['qty'], 2)) ?></div>
                                    </li>
                                <?php endforeach; ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Subtotal</strong>
                                    <span>$<?= htmlspecialchars(number_format($subtotal, 2)) ?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Shipping</strong>
                                    <span>$<?= htmlspecialchars(number_format($shipping, 2)) ?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Total</strong>
                                    <span>$<?= htmlspecialchars(number_format($total, 2)) ?></span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>