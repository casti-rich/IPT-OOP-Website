<?php
session_start();
$checkoutMessage = $_SESSION['checkout_message'] ?? '';
if ($checkoutMessage !== '') {
    unset($_SESSION['checkout_message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Out Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="CSS/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/products-navbar.css">
    <link rel="stylesheet" href="CSS/check-out-page.css">
</head>
<body class="checkout-page">
    <?php include 'products-navbar.php'; ?>

    <?php if ($checkoutMessage !== ''): ?>
        <div class="checkout-message" role="status" aria-live="polite">
            <?= htmlspecialchars($checkoutMessage) ?>
        </div>
    <?php endif; ?>

    <main class="container py-4" aria-label="Cart checkout page">
        <div class="row g-4">
            <section class="col-12 col-lg-8" aria-label="Cart items">
            <?php
            $cart = $_SESSION['cart'] ?? [];
            if (empty($cart)):
            ?>
                <div class="card empty-cart">
                    <div class="card-body">
                        <p class="mb-3">Your cart is empty.</p>
                        <a href="index.php" class="btn action-btn">Continue Shopping</a>
                    </div>
                </div>
            <?php else:
                foreach ($cart as $item):
            ?>
                <article class="card cart-item">
                    <div class="card-body d-flex flex-column flex-md-row align-items-md-center gap-3">
                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="cart-thumb">
                        <div class="flex-grow-1">
                            <p class="item-description mb-2"><?= htmlspecialchars($item['title']) ?></p>
                            <div class="qty-controls" data-max-qty="<?= htmlspecialchars((string) ($item['inventory'] ?? '')) ?>" data-price="<?= htmlspecialchars((string) $item['price']) ?>" data-title="<?= htmlspecialchars($item['title']) ?>" aria-label="Quantity controls for <?= htmlspecialchars($item['title']) ?>">
                                <button class="btn icon-btn" aria-label="Increase quantity">+</button>
                                <span class="qty-value"><?= htmlspecialchars($item['quantity']) ?></span>
                                <button class="btn icon-btn" aria-label="Decrease quantity">-</button>
                            </div>
                        </div>
                        <div class="item-price">$ <?= htmlspecialchars(number_format($item['price'], 2)) ?></div>
                    </div>
                </article>
            <?php
                endforeach;
            endif;
            ?>

            <?php if (!empty($cart)): ?>
                <div class="d-flex justify-content-start clear-cart-row">
                    <form method="post" action="clear_cart.php">
                        <button type="submit" class="btn action-btn clear-cart-btn">Clear Cart</button>
                    </form>
                </div>
            <?php endif; ?>
            </section>

            <aside class="col-12 col-lg-4" aria-label="Checkout summary">
                <div class="card checkout-panel">
                    <div class="card-body">
                        <?php if (!empty($cart)): ?>
                            <?php
                            $itemCount = 0;
                            $subtotal = 0.0;
                            foreach ($cart as $item) {
                                $itemCount += (int) $item['quantity'];
                                $subtotal += (float) $item['price'] * (int) $item['quantity'];
                            }
                            $shipping = 1.00;
                            $total = $subtotal + $shipping;
                            ?>

                            <?php foreach ($cart as $item): ?>
                                <div class="panel-card panel-cart-item" data-title="<?= htmlspecialchars($item['title']) ?>">
                                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="panel-cart-thumb">
                                    <div>
                                        <p class="panel-item-title mb-1"><?= htmlspecialchars($item['title']) ?></p>
                                        <p class="panel-item-meta panel-item-qty">Qty: <?= htmlspecialchars((string) $item['quantity']) ?></p>
                                        <p class="panel-item-meta mb-0">$ <?= htmlspecialchars(number_format((float) $item['price'], 2)) ?> each</p>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="panel-card summary-card">
                                <div class="summary-row">
                                    <span>Qty:</span>
                                    <span id="summary-qty"><?= htmlspecialchars((string) $itemCount) ?></span>
                                </div>
                                <div class="summary-row">
                                    <span>Subtotal:</span>
                                    <span id="summary-subtotal" data-value="<?= htmlspecialchars((string) $subtotal) ?>">$ <?= htmlspecialchars(number_format($subtotal, 2)) ?></span>
                                </div>
                                <div class="summary-row">
                                    <span>Shipping:</span>
                                    <span id="summary-shipping" data-value="<?= htmlspecialchars((string) $shipping) ?>">$ <?= htmlspecialchars(number_format($shipping, 2)) ?></span>
                                </div>
                                <div class="summary-row">
                                    <span>Total:</span>
                                    <span id="summary-total">$ <?= htmlspecialchars(number_format($total, 2)) ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="checkout-wrap">
                            <?php if (!empty($cart)): ?>
                                <form method="post" action="checkout.php" class="d-grid gap-3">
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
                                            <label class="form-check-label" for="payment-gcash">Paypal</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn action-btn checkout-btn">Proceed to Payment</button>
                                </form>
                            <?php else: ?>
                                <button type="button" class="btn action-btn checkout-btn" disabled>Proceed to Payment</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <script src="Scripts/product-quantity.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
