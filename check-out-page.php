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

    <main class="layout" aria-label="Cart checkout page">
        <section class="cart-list" aria-label="Cart items">
            <article class="cart-item">
                <p class="item-description">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt
                </p>

                <div class="qty-controls" aria-label="Quantity controls for item 1">
                    <button class="icon-btn" aria-label="Increase quantity item 1">+</button>
                    <span class="qty-value">0</span>
                    <button class="icon-btn" aria-label="Decrease quantity item 1">-</button>
                </div>
            </article>

            <article class="cart-item">
                <p class="item-description">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt
                </p>

                <div class="qty-controls" aria-label="Quantity controls for item 2">
                    <button class="icon-btn" aria-label="Increase quantity item 2">+</button>
                    <span class="qty-value">0</span>
                    <button class="icon-btn" aria-label="Decrease quantity item 2">-</button>
                </div>
            </article>

            <div class="continue-wrap">
                <button class="action-btn">Continue Shopping</button>
            </div>
        </section>

        <aside class="checkout-panel" aria-label="Checkout summary">
            <div class="panel-card">
                <p class="panel-item-title">Item 1</p>
            </div>

            <div class="panel-card">
                <p class="panel-item-title">Item 2</p>
            </div>

            <div class="panel-card summary-card">
                <div class="summary-row">
                    <span>Qty:</span>
                    <span>xxxx</span>
                </div>
                <div class="summary-row">
                    <span>Shipping:</span>
                    <span>$ 1.00</span>
                </div>
                <div class="summary-row">
                    <span>Total:</span>
                    <span>$ 1.00</span>
                </div>
            </div>

            <div class="checkout-wrap">
                <button class="action-btn checkout-btn">Check Out</button>
            </div>
        </aside>
    </main>

    <script src="Scripts/product-quantity.js"></script>
</body>
</html>
