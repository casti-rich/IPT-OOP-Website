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
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/products-navbar.css">
    <link rel="stylesheet" href="CSS/check-out-page.css">
</head>
<body>
    <?php include 'products-navbar.php'; ?>

    <?php if ($checkoutMessage !== ''): ?>
        <div class="checkout-message" role="status" aria-live="polite">
            <?= htmlspecialchars($checkoutMessage) ?>
        </div>
    <?php endif; ?>

    <main class="layout" aria-label="Cart checkout page">
        <section class="cart-list" aria-label="Cart items">
            <?php
            $cart = $_SESSION['cart'] ?? [];
            if (empty($cart)):
            ?>
                <p class="empty-cart">Your cart is empty.</p>
                <div class="continue-wrap">
                    <a href="index.php" class="action-btn">Continue Shopping</a>
                </div>
            <?php else:
                foreach ($cart as $item):
            ?>
                <article class="cart-item">
                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="cart-thumb">
                    <p class="item-description"><?= htmlspecialchars($item['title']) ?></p>
                    <div class="qty-controls" data-max-qty="<?= htmlspecialchars((string) ($item['inventory'] ?? '')) ?>" aria-label="Quantity controls for <?= htmlspecialchars($item['title']) ?>">
                        <button class="icon-btn" aria-label="Increase quantity">+</button>
                        <span class="qty-value"><?= htmlspecialchars($item['quantity']) ?></span>
                        <button class="icon-btn" aria-label="Decrease quantity">-</button>
                    </div>
                    <div class="item-price">$ <?= htmlspecialchars(number_format($item['price'], 2)) ?></div>
                </article>
            <?php
                endforeach;
            endif;
            ?>

            <?php if (!empty($cart)): ?>
                <div class="continue-wrap clear-cart-wrap">
                    <form method="post" action="clear_cart.php">
                        <button type="submit" class="action-btn clear-cart-btn">Clear Cart</button>
                    </form>
                </div>
            <?php endif; ?>
        </section>

        <aside class="checkout-panel" aria-label="Checkout summary">
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
                    <div class="panel-card panel-cart-item">
                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="panel-cart-thumb">
                        <div>
                            <p class="panel-item-title"><?= htmlspecialchars($item['title']) ?></p>
                            <p class="panel-item-meta">Qty: <?= htmlspecialchars((string) $item['quantity']) ?></p>
                            <p class="panel-item-meta">$ <?= htmlspecialchars(number_format((float) $item['price'], 2)) ?> each</p>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="panel-card summary-card">
                    <div class="summary-row">
                        <span>Qty:</span>
                        <span><?= htmlspecialchars((string) $itemCount) ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>$ <?= htmlspecialchars(number_format($subtotal, 2)) ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span>$ <?= htmlspecialchars(number_format($shipping, 2)) ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Total:</span>
                        <span>$ <?= htmlspecialchars(number_format($total, 2)) ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <div class="checkout-wrap">
                <?php if (!empty($cart)): ?>
                    <form method="post" action="checkout_order.php">
                        <button type="submit" class="action-btn checkout-btn">Check Out</button>
                    </form>
                <?php else: ?>
                    <button type="button" class="action-btn checkout-btn" disabled>Check Out</button>
                <?php endif; ?>
            </div>
        </aside>
    </main>

    <script src="Scripts/product-quantity.js"></script>
</body>
</html>
