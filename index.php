<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STORE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/products-navbar.css">
    <link rel="stylesheet" href="CSS/index.css">
</head>

<body>
    <?php include 'products-navbar.php'; ?>
    <div class="main-content">
        <div class="container">
            <section class="hero">
                <div class="carousel">
                    <div class="slides">
                        <img src="assets/Carousel/banner1.jpg" alt="Stage lighting behind the instruments">
                        <img src="assets/Carousel/banner4.jpg" alt="Wide shot of a keyboard rig">
                        <img src="assets/Carousel/banner5.jpg" alt="Guitar body closeup with glowing light">
                    </div>
                </div>
                <div class="hero-overlay" aria-hidden="true"></div>
                <div class="hero-copy">
                    <p class="hero-eyebrow">Limited drops. Studio grade gear.</p>
                    <h1>Find your next tune.</h1>
                    <p class="hero-subtitle">Handpicked instruments, curated for players who want a bold, modern tone.</p>
                    <div class="hero-actions">
                        <a class="btn btn-primary" href="product_list.php">Shop the collection</a>
                        <a class="btn btn-ghost" href="product_list.php">Browse categories</a>
                    </div>
                </div>
            </section>

            <section class="featured">
                <div class="section-head">
                    <div>
                        <p class="section-eyebrow">Featured picks</p>
                        <h2>Spotlight instruments</h2>
                    </div>
                    <a class="section-link" href="product_list.php">See all products</a>
                </div>
                <div class="product-grid">
                    <a class="product-card" href="LesPaul-Jr._KitaModel.php">
                        <span class="product-tag">New arrival</span>
                        <img src="assets/products/ikuyo_kita_model.jpg" alt="Gibson Les Paul Jr. Kita model">
                        <div class="product-info">
                            <h3>Les Paul Jr. Kita Model</h3>
                            <p>New official collaboration with “Bocchi the Rock!”.</p>
                        </div>
                    </a>
                    <a class="product-card" href="Fender_Rhodes.php">
                        <span class="product-tag">Studio favorite</span>
                        <img src="assets/products/Fender_Rhodes/01.jpg" alt="Fender Rhodes keyboard">
                        <div class="product-info">
                            <h3>Fender Rhodes</h3>
                            <p>Warm, bell-like keys built for soulful sessions.</p>
                        </div>
                    </a>
                </div>
            </section>
        </div>
    </div>
</body>

</html>